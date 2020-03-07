<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security;

use App\Entity\Account;
use App\Infrastructure\Modules\LaDanseService;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RS\DiExtraBundle\Annotation as DI;

/**
 * Class AuthenticationContext
 * @package LaDanse\ServicesBundle\Service
 *
 * @DI\Service(AuthenticationContext::SERVICE_NAME, public=true)
 */
class AuthenticationContext extends LaDanseService
{
    const SERVICE_NAME = 'LaDanse.AuthenticationContext';

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
     * @return bool
     */
    public function isAuthenticated()
    {
        try
        {
            return (true === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'));
        }
        catch(Exception $exception)
        {
            return false;
        }
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
