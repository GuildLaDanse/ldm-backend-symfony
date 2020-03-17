<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\GameData\DTO;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("none")
 */
class GameFaction
{
    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("id")
     */
    protected string $id;

    /**
     * @var integer
     *
     * @Type("integer")
     * @SerializedName("armoryId")
     */
    protected int $armoryId;

    /**
     * @var string
     *
     * @SerializedName("name")
     */
    protected string $name;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return GameFaction
     */
    public function setId(string $id) : GameFaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getArmoryId()
    {
        return $this->armoryId;
    }

    /**
     * @param int $armoryId
     * @return GameFaction
     */
    public function setArmoryId($armoryId) : GameFaction
    {
        $this->armoryId = $armoryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return GameFaction
     */
    public function setName(string $name) : GameFaction
    {
        $this->name = $name;
        return $this;
    }
}