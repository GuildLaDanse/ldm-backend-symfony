<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0\DTO\CreateUser;


use App\Command\SyncAuth0\DTO\FactoryInterface;

class PostUserFactory implements FactoryInterface
{
    public function deserializeResponse($jsonResponse): ?PostUser
    {
        // $serializer = SerializerBuilder::create()->build();

        echo $jsonResponse;

        //return $serializer->deserialize($jsonResponse, 'array<App\Command\SyncAuth0\DTO\ListUsers\User>', 'json');

        return null;
    }
}