<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace Modules\GameData\Command\PostGuild;

use App\Modules\GameData\DTO as GameDataDTO;

class PostGuildCommand
{
    /**
     * @var GameDataDTO\PatchGuild
     */
    private GameDataDTO\PatchGuild $patchGuild;

    /**
     * @param GameDataDTO\PatchGuild $patchGuild
     */
    public function __construct(GameDataDTO\PatchGuild $patchGuild)
    {
        $this->patchGuild = $patchGuild;
    }

    /**
     * @return GameDataDTO\PatchGuild
     */
    public function getPatchGuild(): GameDataDTO\PatchGuild
    {
        return $this->patchGuild;
    }
}