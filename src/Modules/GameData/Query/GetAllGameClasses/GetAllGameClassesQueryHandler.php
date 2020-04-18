<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\GameData\Query\GetAllGameClasses;

use App\Entity\GameData as GameDataEntity;
use App\Infrastructure\Tactician\QueryHandlerInterface;
use App\Modules\Common\MapperException;
use App\Modules\GameData\DTO\GameClassMapper;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class GetAllGameClassesQueryHandler implements QueryHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    /**
     * PostGuildCommandHandler constructor.
     * @param LoggerInterface $logger
     * @param ManagerRegistry $doctrine
     */
    public function __construct(
        LoggerInterface $logger,
        ManagerRegistry $doctrine)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }

    /**
     * @param GetAllGameClassesQuery $query
     *
     * @return array
     *
     * @throws MapperException
     */
    public function handle(GetAllGameClassesQuery $query): array
    {
        $em = $this->doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('g')
            ->from(GameDataEntity\GameClass::class, 'g')
            ->orderBy('g.name', 'ASC');

        $this->logger->debug(
            __CLASS__ . ' created DQL for retrieving GameClasses ',
            [
                'query' => $qb->getDQL()
            ]
        );

        /** @var Query $dbQuery */
        $dbQuery = $qb->getQuery();

        $gameClasses = $dbQuery->getResult();

        return GameClassMapper::mapArray($gameClasses);
    }
}