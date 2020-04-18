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
class User
{
    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("email")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("nickname")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $nickname = null;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("user_id")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $userId = null;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("last_login")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $lastLogin = null;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("app_metadata")
     * @JMSSerializer\Type("App\Command\SyncAuth0\DTO\ListUsers\MetaData")
     *
     * @var MetaData|null
     */
    private ?MetaData $appMetadata = null;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @param string|null $nickname
     * @return User
     */
    public function setNickname(?string $nickname): User
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     * @return User
     */
    public function setUserId(?string $userId): User
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }

    /**
     * @param string|null $lastLogin
     * @return User
     */
    public function setLastLogin(?string $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return MetaData|null
     */
    public function getAppMetadata(): ?MetaData
    {
        return $this->appMetadata;
    }

    /**
     * @param MetaData|null $appMetadata
     * @return User
     */
    public function setAppMetadata(?MetaData $appMetadata): User
    {
        $this->appMetadata = $appMetadata;
        return $this;
    }
}