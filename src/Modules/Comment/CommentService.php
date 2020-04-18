<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Comment;

use App\Entity\Comments\Comment;
use App\Entity\Comments\CommentGroup;
use App\Infrastructure\Modules\UUIDUtils;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class CommentService
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $groupId
     *
     * @return CommentGroup
     *
     * @throws CommentGroupDoesNotExistException
     */
    public function getCommentGroup($groupId)
    {
        $groupRepo = $this->doctrine->getRepository(CommentGroup::class);

        /** @var CommentGroup $group */
        $group = $groupRepo->find($groupId);

        if (null === $group)
        {
            throw new CommentGroupDoesNotExistException("CommentGroup does not exist: " . $groupId);
        }
        else
        {
            return $group;
        }
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function createCommentGroup(): string
    {
       $em = $this->doctrine->getManager();

        $groupId = UUIDUtils::createUUID();
        
        $group = new CommentGroup();

        $group->setId($groupId);
        $group->setCreateDate(new DateTime());

        $em->persist($group);

        return $groupId;
    }

    /**
     * @param $groupId
     *
     * @throws CommentGroupDoesNotExistException
     */
    public function removeCommentGroup($groupId)
    {
        $em = $this->doctrine->getManager();

        $groupRepo = $this->doctrine->getRepository(CommentGroup::class);

        $group = $groupRepo->find($groupId);

        if (null === $group)
        {
            throw new CommentGroupDoesNotExistException("CommentGroup does not exist: " . $groupId);
        }
        else
        {
            $em->remove($group);
        }
    }

    /**
     * @param $commentId
     *
     * @return Comment
     *
     * @throws CommentDoesNotExistException
     */
    public function getComment($commentId)
    {
        $commentRepo = $this->doctrine->getRepository(Comment::class);

        /** @var Comment $comment */
        $comment = $commentRepo->find($commentId);

        if (null === $comment)
        {
            throw new CommentDoesNotExistException("Comment does not exist: " . $commentId);
        }
        else
        {
            return $comment;
        }
    }

    /**
     * @param $groupId
     * @param $account
     * @param $message
     *
     * @throws CommentGroupDoesNotExistException
     */
    public function createComment($groupId, $account, $message)
    {
        $em = $this->doctrine->getManager();
        $groupRepo = $this->doctrine->getRepository(CommentGroup::class);

        /** @var CommentGroup $group */
        $group = $groupRepo->find($groupId);

        if (null === $group)
        {
            throw new CommentGroupDoesNotExistException("CommentGroup does not exist: " . $groupId);
        }
        else
        {
            $comment = new Comment();

            $comment->setId(UUIDUtils::createUUID());
            $comment->setPostDate(new DateTime());
            $comment->setPoster($account);
            $comment->setMessage($message);
            $comment->setGroup($group);

            $group->addComment($comment);

            $em->persist($comment);
        }
    }

    /**
     * @param $commentId
     * @param $message
     *
     * @throws CommentDoesNotExistException
     */
    public function updateComment($commentId, $message)
    {
        $em = $this->doctrine->getManager();
        $commentRepo = $this->doctrine->getRepository(Comment::class);

        $comment = $commentRepo->find($commentId);

        if (null === $comment)
        {
            throw new CommentDoesNotExistException("Post does not exist: " . $commentId);
        }
        else
        {
            $comment->setMessage($message);
            
            $em->persist($comment);
        }
    }
}
