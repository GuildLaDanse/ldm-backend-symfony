<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\Query;

use App\Entity\Event as EventEntity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;

class EventHydrator
{
    /**
     * @var LoggerInterface
     */
    public LoggerInterface $logger;

    /**
     * @var Registry
     */
    public Registry $doctrine;

    /**
     * @var array
     */
    private array $eventIds;

    /**
     * @var DateTime
     */
    private DateTime$onDateTime;

    /**
     * @var bool
     */
    private bool $initialized = false;

    /**
     * @var array
     */
    private array $signUps;

    /**
     * @var array
     */
    private array $forRoles;

    /**
     * @return array
     */
    public function getEventIds(): array
    {
        return $this->eventIds;
    }

    /**
     * @param array $eventIds
     * @return EventHydrator
     */
    public function setEventIds(array $eventIds): EventHydrator
    {
        $this->eventIds = $eventIds;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getOnDateTime(): DateTime
    {
        return $this->onDateTime;
    }

    /**
     * @param DateTime $onDateTime
     * @return EventHydrator
     */
    public function setOnDateTime(DateTime $onDateTime): EventHydrator
    {
        $this->onDateTime = $onDateTime;
        return $this;
    }

    /**
     * @param int $eventId
     *
     * @return array
     */
    public function getSignUps(int $eventId)
    {
        $this->init();

        if ($this->signUps == null)
        {
            return [];
        }

        $result = [];

        foreach($this->signUps as $signUp)
        {
            /** @var EventEntity\SignUp $signUp */
            if ($signUp->getEvent()->getId() == $eventId)
            {
                $result[] = $signUp;
            }
        }

        return $result;
    }

    /**
     * @param int $signUpId
     *
     * @return array
     */
    public function getForRoles(int $signUpId)
    {
        $this->init();

        if ($this->forRoles == null)
        {
            return [];
        }

        $result = [];

        foreach($this->forRoles as $forRole)
        {
            /** @var EventEntity\ForRole $forRole */
            if ($forRole->getSignUp()->getId() == $signUpId)
            {
                $result[] = $forRole;
            }
        }

        return $result;
    }

    private function init()
    {
        if ($this->initialized)
            return;

        if ($this->getEventIds() == null || count($this->getEventIds()) == 0)
        {
            $this->signUps = [];
            $this->initialized = true;

            return;
        }

        $em = $this->doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('signUp', 'account', 'event')
            ->from(EventEntity\SignUp::class, 'signUp')
            ->join('signUp.event', 'event')
            ->join('signUp.account', 'account')
            ->add('where',
                $qb->expr()->in(
                    'event.id',
                    $this->getEventIds()
                )
            );

        /* @var $query Query */
        $query = $qb->getQuery();

        $this->signUps = $query->getResult();

        $signUpIds = [];

        foreach($this->signUps as $signUp)
        {
            /** @var EventEntity\SignUp $signUp */

            $signUpIds[] = $signUp->getId();
        }

        if (count($signUpIds) == 0)
        {
            $this->forRoles = [];
        }
        else
        {
            /** @var QueryBuilder $qb */
            $qb = $em->createQueryBuilder();

            $qb->select('forRole', 'signUp')
                ->from(EventEntity\ForRole::class, 'forRole')
                ->join('forRole.signUp', 'signUp')
                ->add('where',
                    $qb->expr()->in(
                        'signUp.id',
                        $signUpIds
                    )
                );

            /* @var $query Query */
            $query = $qb->getQuery();

            $this->forRoles = $query->getResult();
        }

        $this->initialized = true;
    }
}