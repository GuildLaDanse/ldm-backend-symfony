<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\Forum;

use App\Entity\Account;
use App\Infrastructure\Modules\LaDanseService;
use App\Infrastructure\Modules\UUIDUtils;
use DateTime;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Exception;
use RS\DiExtraBundle\Annotation as DI;
use App\Entity\Forum as ForumEntity;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ForumStatisService
 *
 * @package LaDanse\ForumBundle\Service
 *
 * @DI\Service(ForumStatsService::SERVICE_NAME, public=true)
 */
class ForumStatsService extends LaDanseService
{
    const SERVICE_NAME = 'LaDanse.ForumStatsService';

    /**
     * @param ContainerInterface $container
     *
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @param DateTime $sinceDateTime
     *
     * @return array
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getNewPostsSince($sinceDateTime)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $query Query */
        $query = $em->createQuery(
            $this->createSQLFromTemplate('LaDanseDomainBundle::forum\selectNewPostsSince.sql.twig')
        );
        $query->setParameter('sinceDateTime', $sinceDateTime);

        return $query->getResult();
    }

    /**
     * @param Account $account
     *
     * @return array
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function getUnreadPostsForAccount(Account $account)
    {
        $doc = $this->getDoctrine();
        $em = $doc->getManager();

        $lastVisit = $this->getLastVisitForAccount($account, new DateTime());

        $newPosts = $this->getNewPostsSince($lastVisit);

        /** @var ForumEntity\Post $newPost */
        foreach($newPosts as $newPost)
        {
            if ($newPost->getPoster()->getId() == $account->getId())
            {
                continue;
            }

            $unreadPost = new ForumEntity\UnreadPost();
            $unreadPost->setId(UUIDUtils::createUUID());
            $unreadPost->setAccount($account);
            $unreadPost->setPost($newPost);

            $em->persist($unreadPost);
        }

        $em->flush();

        $this->resetLastVisitForAccount($account);

        /* @var $query Query */
        $query = $em->createQuery(
            $this->createSQLFromTemplate('LaDanseDomainBundle::forum\selectUnreadPostsForAccount.sql.twig')
        );
        $query->setParameter('forAccount', $account);

        $queryResult = $query->getResult();

        $unreadPosts = [];

        /** @var ForumEntity\UnreadPost $unreadPost */
        foreach($queryResult as $unreadPost)
        {
            $unreadPosts[] = $unreadPost->getPost();
        }

        return $unreadPosts;
    }

    /**
     * @param DateTime $sinceDateTime
     *
     * @return array
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getNewTopicsSince($sinceDateTime)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $query Query */
        $query = $em->createQuery(
            $this->createSQLFromTemplate('LaDanseDomainBundle::forum\selectNewTopicsSince.sql.twig')
        );
        $query->setParameter('sinceDateTime', $sinceDateTime);

        return $query->getResult();
    }

    /**
     * @param Account $account
     * @param string $postId
     */
    public function markPostAsRead(Account $account, $postId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->delete('LaDanse\DomainBundle\Entity\Forum\UnreadPost', 'u')
           ->where('u.post = :readPost')
           ->andWhere('u.account = :forAccount')
           ->setParameter('readPost', $postId)
           ->setParameter('forAccount', $account);

        $query = $qb->getQuery();

        $query->getResult();
    }

    /**
     * @param Account $account
     * @param DateTime $default
     *
     * @return DateTime
     */
    private function getLastVisitForAccount($account, DateTime $default = null)
    {
        $doc = $this->getDoctrine();
        $em = $doc->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('v')
            ->from('LaDanse\DomainBundle\Entity\Forum\ForumLastVisit', 'v')
            ->where(
                $qb->expr()->eq('v.account', '?1')
            )
            ->setParameter(1, $account);

        $query = $qb->getQuery();
        $result = $query->getResult();

        if (count($result) == 0)
        {
            return $default;
        }
        else
        {
            /** @var ForumEntity\ForumLastVisit $forumLastVisit */
            $forumLastVisit = $result[0];

            return $forumLastVisit->getLastVisitDate();
        }
    }

    /**
     * @param Account $account
     *
     * @throws Exception
     */
    private function resetLastVisitForAccount($account)
    {
        $doc = $this->getDoctrine();
        $em = $doc->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();

        $qb->select('v')
            ->from('LaDanse\DomainBundle\Entity\Forum\ForumLastVisit', 'v')
            ->where(
                $qb->expr()->eq('v.account', '?1')
            )
            ->setParameter(1, $account);

        $query = $qb->getQuery();
        $result = $query->getResult();

        if (count($result) == 0)
        {
            $forumLastVisit = new ForumEntity\ForumLastVisit();
            $forumLastVisit->setId(UUIDUtils::createUUID());
            $forumLastVisit->setAccount($account);
            $forumLastVisit->setLastVisitDate(new DateTime());

            $em->persist($forumLastVisit);
            $em->flush();
        }
        else
        {
            /** @var ForumEntity\ForumLastVisit $forumLastVisit */
            $forumLastVisit = $result[0];

            $forumLastVisit->setLastVisitDate(new DateTime());

            $em->persist($forumLastVisit);
            $em->flush();
        }
    }
}
