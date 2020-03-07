<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\DTO;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class PutSignUp
{
    /**
     * @var string
     * @Type("string")
     * @SerializedName("signUpType")
     * @Assert\NotBlank()
     */
    private $signUpType;

    /**
     * @var array
     * @Type("array<string>")
     * @SerializedName("roles")
     */
    private $roles;

    /**
     * @return string
     */
    public function getSignUpType(): string
    {
        return $this->signUpType;
    }

    /**
     * @param string $signUpType
     * @return PutSignUp
     */
    public function setSignUpType(string $signUpType): PutSignUp
    {
        $this->signUpType = $signUpType;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return PutSignUp
     */
    public function setRoles(array $roles): PutSignUp
    {
        $this->roles = $roles;
        return $this;
    }
}