<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0\DTO\CreateUser;


use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @JMSSerializer\ExclusionPolicy(JMSSerializer\ExclusionPolicy::ALL)
 */
class PostUser
{
    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("email")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $email;


    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("nickname")
     * @JMSSerializer\Type("string")
     *
     * @var string|null
     */
    private ?string $nickname;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("password")
     * @JMSSerializer\Type("string")
     *
     * @var string
     */
    private string $password;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("connection")
     * @JMSSerializer\Type("string")
     *
     * @var string
     */
    private string $connection;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return PostUser
     */
    public function setEmail(?string $email): PostUser
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
     * @return PostUser
     */
    public function setNickname(?string $nickname): PostUser
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return PostUser
     */
    public function setPassword(string $password): PostUser
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return PostUser
     */
    public function setConnection(string $connection): PostUser
    {
        $this->connection = $connection;
        return $this;
    }
}