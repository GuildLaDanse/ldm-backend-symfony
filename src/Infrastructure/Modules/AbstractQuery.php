<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Modules;

use App\Entity\Account\Account;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AbstractQuery
 *
 * @package LaDanse\CommonBundle\Helper
 */
abstract class AbstractQuery
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
     * @deprecated
     *
     * Returns true if the current request is authenticated, false otherwise
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        return false;
    }

    /**
     * @deprecated
     *
     * Returns the account that is currently logged in. When not authenticated, returns null.
     *
     * @return Account
     */
    protected function getAccount()
    {
        return null;
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
     * @param $data
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

        return $this->runQuery();
    }

    abstract protected function validateInput();

    abstract protected function runQuery();
}
