<?php /** @noinspection PhpDocRedundantThrowsInspection */
declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\GameData;

use App\Infrastructure\Modules\InvalidInputException;
use App\Modules\GameData\DTO as GameDataDTO;
use Exception;
use League\Tactician\CommandBus;
use Modules\GameData\Command\PostGuild\PostGuildCommand;
use Modules\GameData\Command\PostRealm\PostRealmCommand;
use Modules\GameData\Query\GetAllGameClasses\GetAllGameClassesQuery;
use Modules\GameData\Query\GetAllGameFactions\GetAllGameFactionsQuery;
use Modules\GameData\Query\GetAllGameFactions\GetAllGuildsQuery;
use Modules\GameData\Query\GetAllRealms\GetAllRealmsQuery;
use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;

class GameDataService
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CommandBus
     */
    private CommandBus $defaultBus;

    /**
     * @param LoggerInterface $logger
     * @param CommandBus $defaultBus
     */
    public function __construct(LoggerInterface $logger, CommandBus $defaultBus)
    {
        $this->logger = $logger;
        $this->defaultBus = $defaultBus;
    }

    /**
     * @return GameDataDTO\GameRace[]
     */
    public function getAllGameRaces(): array
    {
        $query = new GetAllGameClassesQuery();

        return $this->defaultBus->handle($query);
    }

    /**
     * @return GameDataDTO\GameClass[]
     */
    public function getAllGameClasses(): array
    {
        $query = new GetAllGameClassesQuery();

        return $this->defaultBus->handle($query);
    }

    /**
     * @return GameDataDTO\GameFaction[]
     */
    public function getAllGameFactions() : array
    {
        $query = new GetAllGameFactionsQuery();

        return $this->defaultBus->handle($query);
    }

    /**
     * @return GameDataDTO\Guild[]
     */
    public function getAllGuilds() : array
    {
        $query = new GetAllGuildsQuery();

        return $this->defaultBus->handle($query);
    }

    /**
     * @param GameDataDTO\PatchGuild $patchGuild
     *
     * @return GameDataDTO\Guild
     *
     * @throws GuildAlreadyExistsException
     * @throws RealmDoesNotExistException
     * @throws InvalidInputException
     */
    public function postGuild(GameDataDTO\PatchGuild $patchGuild) : GameDataDTO\Guild
    {
        $command = new PostGuildCommand($patchGuild);

        return $this->defaultBus->handle($command);
    }

    /**
     * @param string $guildId
     * @param GameDataDTO\PatchGuild $patchGuild
     *
     * @throws Exception
     */
    public function patchGuild(string $guildId, GameDataDTO\PatchGuild $patchGuild)
    {
        $this->logger->warning(
            "patchGuild not implemented",
            [
                "guildId" => $guildId,
                "patchGuild" => $patchGuild
            ]);

        throw new Exception("Not yet implemented");
    }

    /**
     * @param string $guildId
     *
     * @throws Exception
     */
    public function deleteGuild(string $guildId): void
    {
        $this->logger->warning(
            "deleteGuild not implemented",
            [
                "guildId" => $guildId
            ]);

        throw new Exception("Not yet implemented");
    }

    /**
     * @return GameDataDTO\Realm[]
     */
    public function getAllRealms() : array
    {
        $query = new GetAllRealmsQuery();

        return $this->defaultBus->handle($query);
    }

    /**
     * @param GameDataDTO\PatchRealm $patchRealm
     *
     * @return GameDataDTO\Realm
     *
     * @throws InvalidInputException
     * @throws RealmAlreadyExistsException
     */
    public function postRealm(GameDataDTO\PatchRealm $patchRealm) : GameDataDTO\Realm
    {
        $command = new PostRealmCommand($patchRealm);

        return $this->defaultBus->handle($command);
    }

    /**
     * @param string $realmId
     * @param DTO\PatchRealm $patchRealm

     */
    public function patchRealm(string $realmId, GameDataDTO\PatchRealm $patchRealm)
    {
        $this->logger->warning(
            "patchRealm not implemented",
            [
                "realmId" => $realmId,
                "patchRealm" => $patchRealm
            ]);

        throw new NotImplementedException("Not yet implemented");
    }

    /**
     * @param string $realmId
     */
    public function deleteRealm(string $realmId)
    {
        $this->logger->warning(
            "deleteRealm not implemented",
            [
                "realmId" => $realmId
            ]);

        throw new NotImplementedException("Not yet implemented");
    }
}