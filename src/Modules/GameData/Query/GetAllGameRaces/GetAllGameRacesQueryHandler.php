<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace Modules\GameData\Query\GetAllGameRaces;

use App\Infrastructure\Tactician\QueryHandlerInterface;
use App\Modules\Common\MapperException;
use App\Modules\GameData\DTO\GameRaceMapper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;

class GetAllGameRacesQueryHander implements QueryHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var Registry
     */
    private Registry $doctrine;

    /**
     * PostGuildCommandHandler constructor.
     * @param LoggerInterface $logger
     * @param Registry $doctrine
     */
    public function __construct(
        LoggerInterface $logger,
        Registry $doctrine)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }

    /**
     * @param GetAllGameRacesQuery $query
     * @return array
     * @throws MapperException
     */
    public function __invoke(GetAllGameRacesQuery $query)
    {
        $em = $this->doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('g', 'faction')
            ->from('LaDanse\DomainBundle\Entity\GameData\GameRace', 'g')
            ->join('g.faction', 'faction')
            ->orderBy('g.name', 'ASC');

        $this->logger->debug(
            __CLASS__ . " created DQL for retrieving GameRaces ",
            [
                "query" => $qb->getDQL()
            ]
        );

        /* @var $query Query */
        $query = $qb->getQuery();

        $gameRaces = $query->getResult();

        return GameRaceMapper::mapArray($gameRaces);
    }
}