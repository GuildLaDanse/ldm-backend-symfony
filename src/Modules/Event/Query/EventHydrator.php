<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\Query;

use App\Entity as Entity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Monolog\Logger;
use RS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service(EventHydrator::SERVICE_NAME, public=true, shared=false)
 */
class EventHydrator
{
    const SERVICE_NAME = 'LaDanse.EventHydrator';

    /**
     * @var $logger Logger
     * @DI\Inject("monolog.logger.ladanse")
     */
    public $logger;

    /**
     * @var $logger Registry
     * @DI\Inject("doctrine")
     */
    public $doctrine;

    /** @var array $eventIds */
    private $eventIds;

    /** @var $onDateTime DateTime */
    private $onDateTime;

    /** @var bool $initialized */
    private $initialized = false;

    /** @var array $signUps */
    private $signUps;

    /** @var array $forRoles */
    private $forRoles;

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
            /** @var \App\Entity\Event\SignUp $signUp */
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
            /** @var \App\Entity\Event\ForRole $forRole */
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
            ->from(Entity\Event\SignUp::class, 'signUp')
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
            /** @var \App\Entity\Event\SignUp $signUp */

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
                ->from(Entity\Event\ForRole::class, 'forRole')
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