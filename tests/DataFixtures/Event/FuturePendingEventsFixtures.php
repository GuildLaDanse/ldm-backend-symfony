<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\Event;


use App\Entity\Account\Account;
use App\Entity\Comments\CommentGroup;
use App\Entity\Event\Event;
use App\Entity\Event\EventStateMachine;
use App\Infrastructure\Modules\UUIDUtils;
use App\Tests\DataFixtures\Account\AccountFixtures;
use DateTime;
use Doctrine\Persistence\ObjectManager;

class FuturePendingEventsFixtures extends AbstractEventFixture
{
    public const PENDING_ID = 10;
    public const PENDING_REF = __CLASS__ . '_pending';

    public const CONFIRMED_ID = 11;
    public const CONFIRMED_REF = __CLASS__ . '_confirmed';

    public const CANCELLED_ID = 12;
    public const CANCELLED_REF = __CLASS__ . '_cancelled';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $this->createEvent(
            $manager,
            $this->getAccount(AccountFixtures::ACCOUNT1),
            self::PENDING_ID,
            EventStateMachine::PENDING,
            self::PENDING_REF
        );

        $this->createEvent(
            $manager,
            $this->getAccount(AccountFixtures::ACCOUNT1),
            self::CONFIRMED_ID,
            EventStateMachine::CONFIRMED,
            self::CONFIRMED_REF
        );

        $this->createEvent(
            $manager,
            $this->getAccount(AccountFixtures::ACCOUNT1),
            self::CANCELLED_ID,
            EventStateMachine::CANCELLED,
            self::CANCELLED_REF
        );
    }

    protected function createEvent(ObjectManager $manager, Account $organiser, int $id, $eventState, $reference): void
    {
        $commentGroup = new CommentGroup();
        $commentGroup
            ->setId(UUIDUtils::createUUID())
            ->setCreateDate(new DateTime())
        ;

        $manager->persist($commentGroup);

        $event = new Event();

        $eventDate = self::getEventDate();

        $event
            ->setId($id)
            ->setName('some name')
            ->setDescription('some desription')
            ->setInviteTime(self::getInviteTime($eventDate))
            ->setStartTime(self::getStartTime($eventDate))
            ->setEndTime(self::getEndTime($eventDate))
            ->setOrganiser($organiser)
            ->setFiniteState($eventState)
            ->setTopicId($commentGroup->getId())
        ;

        $this->enableAssignedId($manager, $event);

        $manager->persist($event);

        $manager->flush();

        $this->addReference($reference, $event);
    }
}