<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event;

use App\Modules\Event\Command\DeleteEvent\DeleteEventCommand;
use App\Modules\Event\Command\DeleteSignUp\DeleteSignUpCommand;
use App\Modules\Event\Command\PostSignUp\PostSignUpCommand;
use App\Modules\Event\Query\GetAllEventsPaged\GetAllEventsPagedQuery;
use App\Modules\Event\Query\GetEventById\GetEventByIdQuery;
use DateTime;
use LaDanse\ServicesBundle\Service\Event\Command\NotifyEventTodayCommand;
use LaDanse\ServicesBundle\Service\Event\Command\PostEventCommand;
use LaDanse\ServicesBundle\Service\Event\Command\PutEventCommand;
use LaDanse\ServicesBundle\Service\Event\Command\PutEventStateCommand;
use LaDanse\ServicesBundle\Service\Event\Command\PutSignUpCommand;
use League\Tactician\CommandBus;
use Psr\Log\LoggerInterface;
use App\Modules\Event\DTO as EventDTO;

class EventService
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
     * Return all events.
     *
     * The result is sorted by invite time (ascending) and limited to 28 days starting from $fromTime (included)
     *
     * @param DateTime $fromTime
     *
     * @return EventDTO\EventPage
     */
    public function getAllEventsPaged(DateTime $fromTime)
    {
        $query = new GetAllEventsPagedQuery($fromTime);

        return $this->defaultBus->handle($query);
    }

    /**
     * Return the event with the given id
     *
     * @param int $eventId id of event to retrieve
     *
     * @return EventDTO\Event
     */
    public function getEventById($eventId): EventDTO\Event
    {
        $query = new GetEventByIdQuery($eventId);

        return $this->defaultBus->handle($query);
    }

    /**
     * Create a new event
     *
     * @param EventDTO\PostEvent $postEvent
     *
     * @return EventDTO\Event
     */
    public function postEvent(EventDTO\PostEvent $postEvent): EventDTO\Event
    {
        $command = new PostEventCommand($postEvent);

        return $this->defaultBus->handle($command);
    }

    /**
     * Update an existing event
     *
     * @param int $eventId
     * @param EventDTO\PutEvent $putEvent
     *
     * @return EventDTO\Event
     */
    public function putEvent(int $eventId, EventDTO\PutEvent $putEvent): EventDTO\Event
    {
        $command = new PutEventCommand($eventId, $putEvent);

        return $this->defaultBus->handle($command);
    }

    /**
     * Update the state of an existing event
     *
     * @param int $eventId
     * @param EventDTO\PutEventState $putEventState
     *
     * @return EventDTO\Event
     */
    public function putEventState($eventId, EventDTO\PutEventState $putEventState): EventDTO\Event
    {
        $command = new PutEventStateCommand($eventId, $putEventState);

        return $this->defaultBus->handle($command);
    }

    /**
     * Delete an existing event
     *
     * @param $eventId
     */
    public function deleteEvent($eventId): void
    {
        /** @var DeleteEventCommand $command */
        $command = new DeleteEventCommand($eventId);

        $this->defaultBus->handle($command);
    }

    /**
     * Create a new sign up for an existing event
     *
     * @param $eventId
     * @param EventDTO\PostSignUp $postSignUp
     *
     * @return EventDTO\Event
     */
    public function postSignUp($eventId, EventDTO\PostSignUp $postSignUp): EventDTO\Event
    {
        $command = new PostSignUpCommand($eventId, $postSignUp);

        return $this->defaultBus->handle($command);
    }

    /**
     * Update an existing sign up
     *
     * @param $eventId
     * @param $signUpId
     *
     * @param EventDTO\PutSignUp $putSignUp
     *
     * @return EventDTO\Event
     */
    public function putSignUp($eventId, $signUpId, EventDTO\PutSignUp $putSignUp): EventDTO\Event
    {
        $command = new PutSignUpCommand($eventId, $signUpId, $putSignUp);

        return $this->defaultBus->handle($command);
    }

    /**
     * Remove an existing sign up
     *
     * @param $eventId
     * @param $signUpId
     *
     * @return EventDTO\Event
     */
    public function deleteSignUp($eventId, $signUpId): EventDTO\Event
    {
        $command = new DeleteSignUpCommand($eventId, $signUpId);

        return $this->defaultBus->handle($command);
    }

    /**
     * Create notification events for all events that happen today
     */
    public function notifyEventsToday()
    {
        $command = new NotifyEventTodayCommand();

        $this->defaultBus->handle($command);
    }
}