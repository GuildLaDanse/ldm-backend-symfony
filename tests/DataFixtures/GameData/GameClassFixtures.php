<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\GameData;


use App\Entity\GameData\GameClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameClassFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createGameClass('Mage', 1));
        $manager->persist($this->createGameClass('Hunter', 2));
        $manager->persist($this->createGameClass('Paladin', 3));
        $manager->persist($this->createGameClass('Warlock', 4));

        $manager->flush();
    }

    private function createGameClass(string $gameClassName, int $armoryId): GameClass
    {
        $gameClass = new GameClass();

        $gameClass
            ->setName($gameClassName)
            ->setArmoryId($armoryId)
        ;

        return $gameClass;
    }
}