<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0;


use App\Command\SyncAuth0\DTO\FactoryInterface;
use Exception;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;

abstract class AbstractAuth0Command extends Command
{
    protected const GET = "GET";
    protected const POST = "POST";

    private const API_BASE_URL = 'https://ladanse.eu.auth0.com';

    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @param string $url
     *
     * @param FactoryInterface $factory
     * @return mixed
     *
     * @throws Exception
     */
    protected function apiCallGet(
        string $url,
        FactoryInterface $factory)
    {
        if ($this->accessToken === null)
            $this->initAuth0Api();

        $client = new Client();

        $response = $client->request(self::GET, self::API_BASE_URL . $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        $responseBody = $response->getBody()->getContents();

        return $factory->deserializeResponse($responseBody);
    }

    /**
     * @param string $url
     * @param $postData
     * @param FactoryInterface $factory
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function apiCallPost(
        string $url,
        $postData,
        FactoryInterface $factory)
    {
        if ($this->accessToken === null)
            $this->initAuth0Api();

        $client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type'  => 'application/json'
            ]
        ]);

        $serializer = SerializerBuilder::create()->build();

        $jsonBody = $serializer->serialize($postData, 'json');

        echo $jsonBody;
        echo "\n";
        echo gettype($jsonBody);
        echo "\n";

        $response = $client->request(self::POST, self::API_BASE_URL . $url, [
            'body' => $jsonBody
        ]);

        $responseBody = $response->getBody()->getContents();

        return $factory->deserializeResponse($responseBody);
    }

    /**
     * @throws Exception
     */
    private function initAuth0Api()
    {
        $client = new Client();

        $response = $client->request('POST', 'https://ladanse.eu.auth0.com/oauth/token', [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => 'n6LattzrWkgr72UGRP9OZnBjJZaHI0re',
                'client_secret' => 'MoZipx04HsJal0T0xHiRUpjeGQXJhCIX2PASIto1mYa6OTmesVWmPmtYLYZP6hC8',
                'audience'      => 'https://ladanse.eu.auth0.com/api/v2/'
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode != 200)
        {
            throw new Exception("Got error while fetching access token from Auth0");
        }

        $responseBody = $response->getBody()->getContents();

        $this->accessToken = json_decode($responseBody)->access_token;
    }
}