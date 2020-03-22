<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace Modules\GameData\Query\GetAllGameFactions;

use App\Infrastructure\Tactician\QueryHandlerInterface;
use App\Modules\Common\MapperException;
use App\Modules\GameData\DTO\GameFactionMapper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;

class GetAllGameFactionsQueryHandler implements QueryHandlerInterface
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
     * @param GetAllGameFactionsQuery $query
     *
     * @return array
     *
     * @throws MapperException
     */
    public function __invoke(GetAllGameFactionsQuery $query)
    {
        $em = $this->doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('g')
            ->from('LaDanse\DomainBundle\Entity\GameData\GameFaction', 'g')
            ->orderBy('g.name', 'ASC');

        $this->logger->debug(
            __CLASS__ . " created DQL for retrieving GameFactions ",
            [
                "query" => $qb->getDQL()
            ]
        );

        /* @var $query Query */
        $query = $qb->getQuery();

        $gameFactions = $query->getResult();

        return GameFactionMapper::mapArray($gameFactions);
    }
}