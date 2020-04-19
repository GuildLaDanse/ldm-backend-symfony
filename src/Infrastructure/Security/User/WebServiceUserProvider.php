<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security\User;

use App\Entity\Account\Account;
use App\Repository\Account\AccountRepository;
use Auth0\JWTAuthBundle\Security\Auth0Service;
use Auth0\JWTAuthBundle\Security\Core\JWTUserProviderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class WebServiceUserProvider implements JWTUserProviderInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var AccountRepository
     */
    private AccountRepository $accountRepository;

    /** @noinspection PhpUnusedParameterInspection */
    public function __construct(Auth0Service $auth0Service, LoggerInterface $logger, AccountRepository $accountRepository)
    {
        $this->logger = $logger;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param $jwt
     *
     * @return Account|UserInterface
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function loadUserByJWT($jwt)
    {
        $account = $this->accountRepository->findByExternalId($jwt->sub);

        if ($account !== null)
        {
            $roles = array();
            $roles[] = 'ROLE_OAUTH_AUTHENTICATED';

            $account->setRoles($roles);

            return $account;
        }

        $roles = array();
        $roles[] = 'ROLE_OAUTH_AUTHENTICATED';

        $account = new Account();

        $account->setEmail($jwt->email);
        $account->setDisplayName($account->getEmail());
        $account->setExternalId($jwt->sub);

        $this->accountRepository->save($account);

        $account->setRoles($roles);

        return $account;
    }

    /**
     * @return UserInterface
     */
    public function getAnonymousUser(): UserInterface
    {
        return new Auth0AnonymousUser();
    }

    /**
     * @param string $username
     *
     * @return Account
     *
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($username): Account
    {
        return $this->accountRepository->findByEmail($username);

        //throw new NotImplementedException('loadUserByUsername - method not implemented - ' . $username);
    }

    /**
     * This method should only be called when functional tests are running.
     *
     * @param UserInterface $user
     *
     * @return Account|UserInterface
     *
     * @throws NonUniqueResultException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Account)
        {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getEmail());
    }

    public function supportsClass($class): bool
    {
        return $class === Account::class;
    }
}