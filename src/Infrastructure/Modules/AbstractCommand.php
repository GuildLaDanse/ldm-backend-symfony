<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Modules;

use App\Entity\Account;
use App\Infrastructure\Security\AuthenticationContext;
use App\Infrastructure\Security\AuthenticationService;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AbstractCommand
 *
 * @package LaDanse\CommonBundle\Helper
 */
abstract class AbstractCommand
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
     * @param array $data
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate($templateName, $data = [])
    {
        $twigEnvironment = $this->container->get('twig');

        return $twigEnvironment->render($templateName, $data);
    }

    /**
     * @param $templateName
     * @param array $data
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createSQLFromTemplate($templateName, $data = [])
    {
        return $this->renderTemplate($templateName, $data);
    }

    public function run()
    {
        $this->validateInput();

        return $this->runCommand();
    }

    /**
     * Returns true if the current request is authenticated, false otherwise
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        /** @var AuthenticationContext $authContext */
        $authContext = $this->container->get(AuthenticationService::SERVICE_NAME)->getCurrentContext();

        return $authContext->isAuthenticated();
    }

    /**
     * Returns the account that is currently logged in. When not authenticated, returns null.
     *
     * @return Account
     */
    protected function getAccount()
    {
        if ($this->isAuthenticated())
        {
            return $this->container->get(AuthenticationService::SERVICE_NAME)->getCurrentContext()->getAccount();
        }

        return null;
    }

    protected function serializeToJson($object)
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($object, 'json');
    }

    abstract protected function validateInput();

    abstract protected function runCommand();
}
