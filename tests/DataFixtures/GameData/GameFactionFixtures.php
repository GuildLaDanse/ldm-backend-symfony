<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\GameData;


use App\Entity\GameData\GameClass;
use App\Entity\GameData\GameFaction;
use App\Entity\GameData\GameRace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameFactionFixtures extends Fixture
{
    public const HORDE_FACTION_REFERENCE = 'horde';
    public const ALLIANCE_FACTION_REFERENCE = 'alliance';
    public const NEUTRAL_FACTION_REFERENCE = 'neutral';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $hordeFaction = $this->createGameFaction('Horde', 1);
        $manager->persist($hordeFaction);

        $allianceFaction = $this->createGameFaction('Alliance', 2);
        $manager->persist($allianceFaction);

        $neutralFaction = $this->createGameFaction('Neutral', 3);
        $manager->persist($neutralFaction);

        $manager->flush();

        $this->addReference(self::HORDE_FACTION_REFERENCE, $hordeFaction);
        $this->addReference(self::ALLIANCE_FACTION_REFERENCE, $allianceFaction);
        $this->addReference(self::NEUTRAL_FACTION_REFERENCE, $neutralFaction);
    }

    private function createGameFaction(string $gameFactionName, int $armoryId): GameFaction
    {
        $gameFaction = new GameFaction();

        $gameFaction
            ->setName($gameFactionName)
            ->setArmoryId($armoryId)
        ;

        return $gameFaction;
    }
}