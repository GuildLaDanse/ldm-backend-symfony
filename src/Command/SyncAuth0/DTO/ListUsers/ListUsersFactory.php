<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0\DTO\ListUsers;


use App\Command\SyncAuth0\DTO\FactoryInterface;
use JMS\Serializer\SerializerBuilder;

class ListUsersFactory implements FactoryInterface
{
    public function deserializeResponse($jsonResponse)
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->deserialize($jsonResponse, 'array<App\Command\SyncAuth0\DTO\ListUsers\User>', 'json');
    }
}