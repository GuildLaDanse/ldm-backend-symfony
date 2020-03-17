<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\GameData\DTO;

use App\Modules\Common\StringReference;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * @ExclusionPolicy("none")
 */
class Guild
{
    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("id")
     */
    private string $id;

    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("name")
     */
    private string $name;

    /**
     * @var StringReference
     *
     * @Type(StringReference::class)
     * @SerializedName("realmReference")
     */
    private StringReference $realmReference;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Guild
     */
    public function setId(string $id): Guild
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Guild
     */
    public function setName(string $name): Guild
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return StringReference
     */
    public function getRealmReference(): StringReference
    {
        return $this->realmReference;
    }

    /**
     * @param StringReference $realmReference
     * @return Guild
     */
    public function setRealmReference(StringReference $realmReference): Guild
    {
        $this->realmReference = $realmReference;
        return $this;
    }
}