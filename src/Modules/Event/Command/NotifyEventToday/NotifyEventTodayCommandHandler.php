<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace LaDanse\ServicesBundle\Service\Event\Command;

use App\Entity\Event as EventEntity;
use App\FSM\EventStateMachine;
use App\Infrastructure\Tactician\CommandHandlerInterface;
use App\Modules\Activity\ActivityEvent;
use App\Modules\Activity\ActivityType;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotifyEventTodayCommandHandler implements CommandHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    public LoggerInterface $logger;

    /**
     * @var EventDispatcherInterface
     */
    public EventDispatcherInterface $eventDispatcher;

    /**
     * @var Registry
     */
    public Registry $doctrine;

    public function __construct(
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher,
        Registry $doctrine)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->doctrine = $doctrine;
    }

    public function __invoke(NotifyEventTodayCommand $command)
    {
        $em = $this->doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $startToday = (new DateTime())->setTime(0, 0, 0);
        $endToday = (new DateTime())->setTime(23, 59, 59);

        $qb->select('e')
            ->from('LaDanse\DomainBundle\Entity\Event', 'e')
            ->where('e.inviteTime >= :startToday')
            ->andWhere('e.inviteTime <= :endToday')
            ->andWhere('e.state = \'' . EventStateMachine::CONFIRMED . '\'' )
            ->orderBy('e.inviteTime', 'ASC');

        $qb->setParameter('startToday', $startToday)
            ->setParameter('endToday', $endToday);

        $this->logger->debug(
            __CLASS__ . " created DQL for retrieving Events ",
            [
                "query" => $qb->getDQL()
            ]
        );

        /* @var $query Query */
        $query = $qb->getQuery();

        $events = $query->getResult();

        /** @var EventEntity\Event $event */
        foreach ($events as $event)
        {
            $this->eventDispatcher->dispatch(
                new ActivityEvent(
                    ActivityType::EVENT_TODAY,
                    null,
                    [
                        'event' => $event->toJson()
                    ]
                )
            );
        }
    }
}