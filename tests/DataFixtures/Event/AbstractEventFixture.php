<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\Event;


use App\Tests\DataFixtures\AbstractFixture;
use DateInterval;
use DateTime;

abstract class AbstractEventFixture extends AbstractFixture
{
    public static function getEventDate(): DateTime
    {
        return (new DateTime())->add(new DateInterval('P10D'));
    }

    public static function getInviteTime(DateTime $eventDate): DateTime
    {
        $newDate = clone $eventDate;

        return $newDate->setTime(19, 15, 0);
    }

    public static function getStartTime(DateTime $eventDate): DateTime
    {
        $newDate = clone $eventDate;

        return $newDate->setTime(19, 30, 0);
    }

    public static function getEndTime(DateTime $eventDate): DateTime
    {
        $newDate = clone $eventDate;

        return $newDate->setTime(22, 0, 0);
    }
}