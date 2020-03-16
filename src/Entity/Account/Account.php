<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="Account")
 */
class Account implements UserInterface, EquatableInterface
{
    const REPOSITORY = 'LaDanseDomainBundle:Account';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    protected string $displayName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected string $externalId;

    /**
     * @var array
     */
    protected array $roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = array();
    }

    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return false;
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
     * Set displayName
     *
     * @param string $displayName
     *
     * @return Account
     */
    public function setDisplayName($displayName): Account
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Account
     */
    public function setEmail(string $email): Account
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     *
     * @return Account
     */
    public function setExternalId(string $externalId): Account
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        throw new NotImplementedException("getPassword() is not implemented for JWT Users");
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        throw new NotImplementedException("getSalt() is not implemented for JWT Users");
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // do nothing
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Account) {
            return false;
        }

        if ($this->getEmail() !== $user->getEmail())
        {
            return false;
        }

        return true;
    }
}
