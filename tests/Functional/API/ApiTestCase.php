<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API;


use App\Entity\Account\Account;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

abstract class ApiTestCase extends WebTestCase
{
    /** @var KernelBrowser|null  */
    protected ?KernelBrowser $client = null;

    /**
     * @param string $email
     */
    protected function logIn(string $email): void
    {
        $session = self::$container->get('session');

        $firewallName = 'secured_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        $token = new PostAuthenticationGuardToken($this->createAccount($email), $firewallName, ['ROLE_OAUTH_AUTHENTICATED']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function apiGet(string $url)
    {
        return $this->client->request('GET', $url);
    }

    protected function apiPost(string $url, $object)
    {
        try
        {
            return $this->client->request(
                'POST',
                $url,
                [], [], [],
                json_encode($object, JSON_THROW_ON_ERROR, 512));
        }
        /** @noinspection PhpUndefinedClassInspection */
        catch (\JsonException $e)
        {
            throw new InvalidArgumentException('Could not encode object into json ' . $e->getMessage());
        }
    }

    protected function responseAsObject()
    {
        try
        {
            return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }
            /** @noinspection PhpUndefinedClassInspection */
        catch (\JsonException $e)
        {
            throw new InvalidArgumentException('Could not decode json into object ' . $e->getMessage());
        }
    }

    protected function assertStatusCode($expectedstatusCode): void
    {
        $this->assertEquals($expectedstatusCode, $this->client->getResponse()->getStatusCode());
    }

    protected function createAccount(string $email): Account
    {
        $account = new Account();

        $account->setEmail($email);
        $account->setDisplayName('display name');

        return $account;
    }
}