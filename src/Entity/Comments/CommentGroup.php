<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Entity\Comments;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Comments\CommentGroupRepository")
 * @ORM\Table(name="CommentGroup", options={"collate":"utf8mb4_0900_ai_ci", "charset":"utf8mb4"}))
 */
class CommentGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="groupId", type="guid")
     * @ORM\Id
     */
    private int $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="postDate", type="datetime")
     */
    private DateTime $createDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="group", cascade={"persist", "remove"})
     */
    protected ArrayCollection $comments;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createDate
     *
     * @param DateTime $createDate
     * @return CommentGroup
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Add posts
     *
     * @param Comment $comment
     * @return CommentGroup
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return CommentGroup
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
