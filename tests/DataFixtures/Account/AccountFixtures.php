<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\Account;


use App\Entity\Account\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public const EMAIL_ACCOUNT1 = 'user1@example.com';
    public const EMAIL_ACCOUNT2 = 'user2@example.com';
    public const EMAIL_ACCOUNT3 = 'user2@example.com';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createAccount(self::EMAIL_ACCOUNT1));
        $manager->persist($this->createAccount(self::EMAIL_ACCOUNT2));
        $manager->persist($this->createAccount(self::EMAIL_ACCOUNT3));

        $manager->flush();
    }

    private function createAccount(string $email): Account
    {
        $account = new Account();

        $account
            ->setEmail($email)
            ->setDisplayName($email)
            ->setExternalId('external_id')
        ;

        return $account;
    }
}