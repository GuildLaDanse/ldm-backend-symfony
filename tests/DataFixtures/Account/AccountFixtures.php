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
    public const EMAIL_ACCOUNT3 = 'user3@example.com';

    public const ACCOUNT1 = self::EMAIL_ACCOUNT1;
    public const ACCOUNT2 = self::EMAIL_ACCOUNT2;
    public const ACCOUNT3 = self::EMAIL_ACCOUNT3;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $account1 = $this->createAccount(self::EMAIL_ACCOUNT1);
        $manager->persist($account1);

        $account2 = $this->createAccount(self::EMAIL_ACCOUNT2);
        $manager->persist($account2);

        $account3 = $this->createAccount(self::EMAIL_ACCOUNT3);
        $manager->persist($account3);

        $manager->flush();

        $this->addReference(self::ACCOUNT1, $account1);
        $this->addReference(self::ACCOUNT2, $account2);
        $this->addReference(self::ACCOUNT3, $account3);
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