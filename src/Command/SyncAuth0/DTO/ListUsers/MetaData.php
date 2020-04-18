<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0\DTO\ListUsers;

use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @JMSSerializer\ExclusionPolicy(JMSSerializer\ExclusionPolicy::ALL)
 */
class MetaData
{
    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("ladanse_legacy_id")
     * @JMSSerializer\Type("int")
     *
     * @var int|null
     */
    private ?int $ladanseLegacyId;

    /**
     * @return int|null
     */
    public function getLadanseLegacyId(): ?int
    {
        return $this->ladanseLegacyId;
    }

    /**
     * @param int|null $ladanseLegacyId
     * @return MetaData
     */
    public function setLadanseLegacyId(?int $ladanseLegacyId): MetaData
    {
        $this->ladanseLegacyId = $ladanseLegacyId;
        return $this;
    }
}