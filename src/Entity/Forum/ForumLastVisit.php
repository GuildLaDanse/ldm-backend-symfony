<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Entity\Forum;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account\Account;

/**
 * Topic
 *
 * @ORM\Table(name="ForumLastVisit", options={"collate":"utf8mb4_0900_ai_ci", "charset":"utf8mb4"}))
 * @ORM\Entity
 */
class ForumLastVisit
{
    const REPOSITORY = 'LaDanseDomainBundle:Forum\ForumLastVisit';

    /**
     * @var integer
     *
     * @ORM\Column(name="visitId", type="guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="lastVisitDate", type="datetime")
     */
    private $lastVisitDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(name="accountId", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * Set id
     *
     * @param string $id
     * @return ForumLastVisit
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastVisitDate
     *
     * @param DateTime $lastVisitDate
     * @return ForumLastVisit
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->lastVisitDate = $lastVisitDate;

        return $this;
    }

    /**
     * Get lastVisitDate
     *
     * @return DateTime
     */
    public function getLastVisitDate()
    {
        return $this->lastVisitDate;
    }

    /**
     * Set account
     *
     * @param Account $account
     * @return ForumLastVisit
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
