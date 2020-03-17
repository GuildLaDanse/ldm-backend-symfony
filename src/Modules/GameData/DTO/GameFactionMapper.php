<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\GameData\DTO;

use App\Entity\GameData as GameDataEntity;
use App\Modules\Common\MapperException;

class GameFactionMapper
{
    /**
     * @param GameDataEntity\GameFaction $gameFaction
     *
     * @return GameFaction
     */
    public static function mapSingle(GameDataEntity\GameFaction $gameFaction) : GameFaction
    {
        $dtoGameFaction = new GameFaction();

        $dtoGameFaction->setId($gameFaction->getId());
        $dtoGameFaction->setArmoryId($gameFaction->getId());
        $dtoGameFaction->setName($gameFaction->getName());

        return $dtoGameFaction;
    }

    /**
     * @param array $gameFactions
     *
     * @return array
     *
     * @throws MapperException
     */
    public static function mapArray(array $gameFactions) : array
    {
        $dtoGameFactionArray = [];

        foreach($gameFactions as $gameFaction)
        {
            if (!($gameFaction instanceof GameDataEntity\GameFaction))
            {
                throw new MapperException('Element in array is not of type Entity\GameData\GameFaction');
            }

            /** @var GameDataEntity\GameFaction $gameFaction */
            $dtoGameFactionArray[] = GameFactionMapper::mapSingle($gameFaction);
        }

        return $dtoGameFactionArray;
    }
}