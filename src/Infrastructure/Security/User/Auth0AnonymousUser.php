<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Security\User;


use App\Entity\Account\Account;

class Auth0AnonymousUser extends Account
{
    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return true;
    }
}