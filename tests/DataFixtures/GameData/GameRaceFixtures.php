<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\GameData;


use App\Entity\GameData\GameFaction;
use App\Entity\GameData\GameRace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameRaceFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createGameRace('Dwarf', 1,
            $this->getGameFaction(GameFactionFixtures::ALLIANCE_FACTION_REFERENCE)));
        $manager->persist($this->createGameRace('Human', 2,
            $this->getGameFaction(GameFactionFixtures::ALLIANCE_FACTION_REFERENCE)));
        $manager->persist($this->createGameRace('Blood Elf', 3,
            $this->getGameFaction(GameFactionFixtures::HORDE_FACTION_REFERENCE)));
        $manager->persist($this->createGameRace('Troll', 4,
            $this->getGameFaction(GameFactionFixtures::HORDE_FACTION_REFERENCE)));

        $manager->flush();
    }

    private function createGameRace(string $gameRaceName, int $armoryId, GameFaction $gameFaction): GameRace
    {
        $gameRace = new GameRace();

        $gameRace
            ->setName($gameRaceName)
            ->setArmoryId($armoryId)
            ->setFaction($gameFaction)
        ;

        return $gameRace;
    }

    public function getGameFaction($reference): GameFaction
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getReference($reference);
    }
}