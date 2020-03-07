<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security;


use App\Infrastructure\Modules\LaDanseService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RS\DiExtraBundle\Annotation as DI;

/**
 * Class AuthenticationService
 * @package LaDanse\ServicesBundle\Service
 *
 * @DI\Service(AuthenticationService::SERVICE_NAME, public=true)
 */
class AuthenticationService extends LaDanseService
{
    const SERVICE_NAME = 'LaDanse.AuthenticationService';

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
     * @return AuthenticationContext
     */
    public function getCurrentContext(): AuthenticationContext
    {
        /** @var AuthenticationContext $authenticationContext */
        $authenticationContext = $this->container->get(AuthenticationContext::SERVICE_NAME);

        return $authenticationContext;
    }
}
