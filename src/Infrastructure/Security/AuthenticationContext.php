<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security;

use App\Entity\Account\Account;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationContext
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return !$this->getAccount()->isAnonymous();
    }

    /**
     * @return int
     */
    public function getId()
    {
        if ($this->isAuthenticated())
        {
            return $this->tokenStorage->getToken()->getUser()->getId();
        }
        else
        {
            return -1;
        }
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        /** @var Account $account */
        $account = $this->tokenStorage->getToken()->getUser();

        return $account;
    }
}
