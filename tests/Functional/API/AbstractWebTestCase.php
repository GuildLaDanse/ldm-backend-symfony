<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API;


use App\Entity\Account\Account;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @param KernelBrowser $client
     */
    protected function logIn(KernelBrowser $client): void
    {
        $session = self::$container->get('session');

        $firewallName = 'secured_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        $token = new PostAuthenticationGuardToken($this->createAccount(), $firewallName, ['ROLE_OAUTH_AUTHENTICATED']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    protected function createAccount(): Account
    {
        $account = new Account();

        $account->setEmail('bavo@bavoderidder.com');
        $account->setDisplayName('Leto');

        return $account;
    }
}