<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Character;

use App\Modules\Character\Command\CharacterSessionImpl;
use App\Modules\Character\Command\CreateGuildSyncSession\CreateGuildSyncSessionCommand;
use App\Modules\Character\Command\DeleteClaim\DeleteClaimCommand;
use App\Modules\Character\Command\PatchCharacter\PatchCharacterCommand;
use App\Modules\Character\Command\PostClaim\PostClaimCommand;
use App\Modules\Character\Command\PutClaim\PutClaimCommand;
use App\Modules\Character\Command\TrackCharacter\TrackCharacterCommand;
use App\Modules\Character\Command\UntrackCharacter\UntrackCharacterCommand;
use App\Modules\Character\DTO as CharacterDTO;
use App\Modules\Character\DTO\PatchCharacter;
use App\Modules\Character\DTO\PatchClaim;
use App\Modules\Character\DTO\SearchCriteria;
use App\Modules\Character\Query\CharactersByCriteria\CharactersByCriteriaQuery;
use App\Modules\Character\Query\CharactersClaimedByAccount\CharactersClaimedByAccountQuery;
use App\Modules\Character\Query\GetAllCharactersInGuild\GetAllCharactersInGuildQuery;
use App\Modules\Character\Query\GetAllClaimedCharacters\GetAllClaimedCharactersQuery;
use App\Modules\Character\Query\GetCharacterById\GetCharacterByIdQuery;
use App\Modules\Common\StringReference;
use DateTime;
use Exception;
use League\Tactician\CommandBus;
use Psr\Log\LoggerInterface;

class CharacterService
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
     * @param int $characterId
     * @param DateTime|null $onDateTime
     *
     * @return CharacterDTO\Character|null
     *
     * @throws Exception
     */
    public function getCharacterById(int $characterId, DateTime $onDateTime = null): ?CharacterDTO\Character
    {
        if ($onDateTime === null)
        {
            $onDateTime = new DateTime();
        }

        $query = new GetCharacterByIdQuery($characterId, $onDateTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * Returns an array of all characters who were in the guild on $onDateTime
     * Properties are taken as valid on $onDateTime
     *
     * @param StringReference $guildReference
     * @param DateTime $onDateTime if left null, the current date and time is used
     *
     * @return array
     *
     * @throws Exception
     */
    public function getAllCharactersInGuild(StringReference $guildReference, DateTime $onDateTime = null): array
    {
        if ($onDateTime === null)
        {
            $onDateTime = new DateTime();
        }

        $query = new GetAllCharactersInGuildQuery($guildReference, $onDateTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * @param int $accountId
     * @param DateTime $onDateTime
     *
     * @return array
     *
     * @throws Exception
     */
    public function getCharactersClaimedByAccount(int $accountId, DateTime $onDateTime = null) : array
    {
        if ($onDateTime === null)
        {
            $onDateTime = new DateTime();
        }

        $query = new CharactersClaimedByAccountQuery($accountId, $onDateTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * @param DateTime $onDateTime
     *
     * @return array
     *
     * @throws Exception
     */
    public function getAllClaimedCharacters(DateTime $onDateTime = null) : array
    {
        if ($onDateTime === null)
        {
            $onDateTime = new DateTime();
        }

        $query = new GetAllClaimedCharactersQuery($onDateTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @param DateTime|null $onDateTime
     *
     * @return array
     *
     * @throws Exception
     */
    public function getCharactersByCriteria(SearchCriteria $searchCriteria, DateTime $onDateTime = null) : array
    {
        if ($onDateTime === null)
        {
            $onDateTime = new DateTime();
        }

        $query = new CharactersByCriteriaQuery($searchCriteria,$onDateTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * @param CharacterSession $characterSession
     * @param PatchCharacter $patchCharacter
     *
     * @return CharacterDTO\Character
     *
     * @throws Exception
     */
    public function trackCharacter(CharacterSession $characterSession, PatchCharacter $patchCharacter): CharacterDTO\Character
    {
        $command = new TrackCharacterCommand($characterSession, $patchCharacter);

        $characterId = $this->defaultBus->handle($command);

        return $this->getCharacterById($characterId);
    }

    /**
     * @param CharacterSession $characterSession
     * @param int $characterId
     */
    public function untrackCharacter(CharacterSession $characterSession, int $characterId)
    {
        $command = new UntrackCharacterCommand($characterSession, $characterId);

        $this->defaultBus->handle($command);
    }

    /**
     * @param CharacterSession $characterSession
     * @param int $characterId
     * @param PatchCharacter $patchCharacter
     *
     * @return CharacterDTO\Character
     *
     * @throws Exception
     */
    public function patchCharacter(
        CharacterSession $characterSession,
        int $characterId,
        PatchCharacter $patchCharacter): CharacterDTO\Character
    {
        $command = new PatchCharacterCommand($characterId, $patchCharacter, $characterSession);

        $this->defaultBus->handle($command);

        return $this->getCharacterById($characterId);
    }

    /**
     * @param int $characterId
     * @param int $accountId
     * @param PatchClaim $patchClaim
     *
     * @return CharacterDTO\Character
     *
     * @throws Exception
     */
    public function postClaim(int $characterId, int $accountId, PatchClaim $patchClaim): CharacterDTO\Character
    {
        $command = new PostClaimCommand($characterId, $accountId, $patchClaim);

        $this->defaultBus->handle($command);

        return $this->getCharacterById($characterId);
    }

    /**
     * @param int $characterId
     * @param PatchClaim $patchClaim
     *
     * @return CharacterDTO\Character
     *
     * @throws Exception
     */
    public function putClaim(int $characterId, PatchClaim $patchClaim): CharacterDTO\Character
    {
        $command = new PutClaimCommand($characterId, $patchClaim);

        $this->defaultBus->handle($command);

        return $this->getCharacterById($characterId);
    }

    /**
     * @param int $characterId
     *
     * @return CharacterDto\Character
     *
     * @throws Exception
     */
    public function deleteClaim(int $characterId): CharacterDto\Character
    {
        $command = new DeleteClaimCommand($characterId);

        $this->defaultBus->handle($command);

        return $this->getCharacterById($characterId);
    }

    /**
     * @param StringReference $guildId
     *
     * @return CharacterSession
     */
    public function createGuildSyncSession(StringReference $guildId) : CharacterSession
    {
        $command = new CreateGuildSyncSessionCommand($guildId);

        return $this->defaultBus->handle($command);
    }

    /**
     * @param CharacterSession $characterSession
     *
     * @return CharacterService
     *
     * @throws Exception
     */
    public function endCharacterSession(CharacterSession $characterSession) : CharacterService
    {
        if (!($characterSession instanceof CharacterSessionImpl))
        {
            throw new Exception("Unknown implementation for CharacterSession");
        }

        /** @var CharacterSessionImpl $characterSessionImpl */
        $characterSessionImpl = $characterSession;

        $characterSessionImpl->endSession();

        return $this;
    }
}