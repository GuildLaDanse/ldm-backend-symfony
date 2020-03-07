<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Modules;

use App\Infrastructure\Security\AuthenticationService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LaDanseService
 *
 * @package LaDanse\CommonBundle\Helper
 */
class LaDanseService
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param $templateName
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate($templateName)
    {
        $twigEnvironment = $this->container->get('twig');

        return $twigEnvironment->render($templateName);
    }

    /**
     * @param $templateName
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createSQLFromTemplate($templateName)
    {
        return $this->renderTemplate($templateName);
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->container->get('logger');
    }

    /**
     * @return AuthenticationService
     */
    protected function getAuthenticationService()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->container->get(AuthenticationService::SERVICE_NAME);

        return $authenticationService;
    }

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    protected function get($serviceName)
    {
        return $this->container->get($serviceName);
    }
}
