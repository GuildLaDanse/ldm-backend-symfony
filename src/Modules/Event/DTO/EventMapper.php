<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\DTO;

use App\Modules\Common\MapperException;
use App\Modules\Event\Query\EventHydrator;
use DateTime;
use DateTimeZone;
use App\Entity as Entity;
use App\Modules\Common as CommonDTO;
use App\Modules\Event\DTO as EventDTO;

class EventMapper
{
    /**
     * @param Entity\Event $event
     * @param EventHydrator $eventHydrator
     *
     * @return Event
     *
     * @throws MapperException
     */
    public static function mapSingle(Entity\Event $event, EventHydrator $eventHydrator) : EventDTO\Event
    {
        $dtoEvent = new EventDTO\Event();

        $dtoEvent
            ->setId($event->getId())
            ->setName($event->getName())
            ->setDescription($event->getDescription())
            ->setInviteTime(EventMapper::toRealmServerTime($event->getInviteTime()))
            ->setStartTime(EventMapper::toRealmServerTime($event->getStartTime()))
            ->setEndTime(EventMapper::toRealmServerTime($event->getEndTime()))
            ->setState($event->getFiniteState())
            ->setOrganiser(
                new CommonDTO\AccountReference(
                    $event->getOrganiser()->getId(),
                    $event->getOrganiser()->getDisplayName())
            )
            ->setCommentGroup(
                new CommonDTO\CommentGroupReference($event->getTopicId())
            );

        $signUpDtos = [];

        /** @var Entity\SignUp $signUp */
        foreach($eventHydrator->getSignUps($event->getId()) as $signUp)
        {
            $roles = [];

            if ($signUp->getType() != Entity\SignUpType::ABSENCE)
            {
                /** @var Entity\ForRole $role */
                foreach($eventHydrator->getForRoles($signUp->getId()) as $role)
                {
                    $roles[] = $role->getRole();
                }
            }

            $dtoSignUp = new EventDTO\SignUp();

            $dtoSignUp
                ->setId($signUp->getId())
                ->setAccount(
                    new CommonDTO\AccountReference(
                        $signUp->getAccount()->getId(),
                        $signUp->getAccount()->getDisplayName())
                )
                ->setType($signUp->getType())
                ->setRoles($roles);

            $signUpDtos[] = $dtoSignUp;
        }

        $dtoEvent->setSignUps($signUpDtos);

        return $dtoEvent;
    }

    /**
     * @param array $events
     * @param EventHydrator $eventHydrator
     *
     * @return array
     *
     * @throws MapperException
     */
    public static function mapArray(array $events, EventHydrator $eventHydrator) : array
    {
        $dtoArray = [];

        foreach($events as $event)
        {
            if (!($event instanceof Entity\Event))
            {
                throw new MapperException('Element in array is not of type Entity\Event');
            }

            /** @var Entity\Event $event */
            $dtoArray[] = EventMapper::mapSingle($event, $eventHydrator);
        }

        return $dtoArray;
    }

    /**
     * @param DateTime $date
     *
     * @return DateTime
     *
     * @throws MapperException
     */
    private static function toRealmServerTime(DateTime $date) : DateTime
    {
        if ((new DateTimeZone('UTC'))->getOffset($date) == 0)
        {
            return (clone $date)->setTimezone(new DateTimeZone('Europe/Paris'));
        }
        else
        {
            throw new MapperException("The DateTime return from the database was not in UTC");
        }
    }
}