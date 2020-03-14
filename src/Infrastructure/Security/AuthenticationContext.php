<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security;

use App\Entity\Account\Account;
use App\Infrastructure\Modules\LaDanseService;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthenticationContext extends LaDanseService
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
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
            return $this->get('security.token_storage')->getToken()->getUser()->getId();
        }
        else
        {
            return -1;
        }
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }
}
