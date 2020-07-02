<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\Query\GetEventById;

use App\Entity\Event as EntityEvent;
use App\Infrastructure\Security\AuthenticationService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Modules\Event\DTO as EventDTO;
use App\Infrastructure\Authorization\AuthorizationService;
use App\Infrastructure\Authorization\NotAuthorizedException;
use App\Infrastructure\Authorization\ResourceByValue;
use App\Infrastructure\Authorization\SubjectReference;
use App\Modules\Activity\ActivityType;
use App\Modules\Common\MapperException;
use App\Modules\Event\DTO\EventMapper;
use App\Modules\Event\EventDoesNotExistException;
use App\Modules\Event\Query\EventHydrator;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class GetEventByIdQueryHandler implements MessageHandlerInterface
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
     * @var EventHydrator
     */
    private EventHydrator $eventHydrator;

    /**
     * @var AuthenticationService
     */
    private AuthenticationService $authenticationService;

    /**
     * @var AuthorizationService
     */
    private AuthorizationService $authzService;

    public function __construct(
        LoggerInterface $logger,
        ManagerRegistry $doctrine,
        EventHydrator $eventHydrator,
        AuthenticationService $authenticationService,
        AuthorizationService $authzService)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
        $this->eventHydrator = $eventHydrator;
        $this->authenticationService = $authenticationService;
        $this->authzService = $authzService;
    }

    /**
     * @param GetEventByIdQuery $query
     *
     * @return EventDTO\Event
     *
     * @throws EventDoesNotExistException
     * @throws NotAuthorizedException
     * @throws MapperException
     */
    public function handle(GetEventByIdQuery $query): EventDTO\Event
    {
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository(EntityEvent\Event::class);

        /** @var EntityEvent\Event $event */
        $event = $repository->find($query->getEventId());

        if ($event === null)
        {
            throw new EventDoesNotExistException('Event does not exist');
        }

        $this->authzService->allowOrThrow(
            new SubjectReference($this->authenticationService->getCurrentContext()->getAccount()),
            ActivityType::EVENT_VIEW,
            new ResourceByValue(EntityEvent\Event::class, $event)
        );

        $eventIds = [$event->getId()];

        $this->eventHydrator->setEventIds($eventIds);

        return EventMapper::mapSingle($event, $this->eventHydrator);
    }
}