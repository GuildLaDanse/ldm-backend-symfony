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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ExclusionPolicy("none")
 */
class PatchGuild
{
    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("name")
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @var StringReference
     *
     * @Type(StringReference::class)
     * @SerializedName("realmId")
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private StringReference $realmId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PatchGuild
     */
    public function setName(string $name): PatchGuild
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return StringReference
     */
    public function getRealmId(): StringReference
    {
        return $this->realmId;
    }

    /**
     * @param StringReference $realmId
     * @return PatchGuild
     */
    public function setRealmId(StringReference $realmId): PatchGuild
    {
        $this->realmId = $realmId;
        return $this;
    }
}