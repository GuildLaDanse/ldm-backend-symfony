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
     * @param string $email
     */
    protected function logIn(KernelBrowser $client, string $email): void
    {
        $session = self::$container->get('session');

        $firewallName = 'secured_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        $token = new PostAuthenticationGuardToken($this->createAccount($email), $firewallName, ['ROLE_OAUTH_AUTHENTICATED']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    protected function createAccount(string $email): Account
    {
        $account = new Account();

        $account->setEmail($email);
        $account->setDisplayName('display name');

        return $account;
    }
}