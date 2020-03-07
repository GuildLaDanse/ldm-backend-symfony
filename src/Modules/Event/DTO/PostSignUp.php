<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\DTO;

use App\Modules\Common\IntegerReference;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class PostSignUp
{
    /**
     * @var IntegerReference
     * @Type(IntegerReference::class)
     * @SerializedName("accountReference")
     * @Assert\NotNull()
     */
    private $accountReference;

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
     * @return IntegerReference
     */
    public function getAccountReference(): IntegerReference
    {
        return $this->accountReference;
    }

    /**
     * @param IntegerReference $accountReference
     * @return PostSignUp
     */
    public function setAccountReference(IntegerReference $accountReference): PostSignUp
    {
        $this->accountReference = $accountReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignUpType(): string
    {
        return $this->signUpType;
    }

    /**
     * @param string $signUpType
     * @return PostSignUp
     */
    public function setSignUpType(string $signUpType): PostSignUp
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
     * @return PostSignUp
     */
    public function setRoles(array $roles): PostSignUp
    {
        $this->roles = $roles;
        return $this;
    }
}