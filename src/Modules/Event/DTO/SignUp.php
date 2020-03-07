<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Event\DTO;

use App\Modules\Common\AccountReference;
use JMS\Serializer\Annotation\SerializedName;

class SignUp
{
    /**
     * @var int
     * @SerializedName("id")
     */
    protected $id;

    /**
     * @var AccountReference
     * @SerializedName("accountRef")
     */
    protected $account;

    /**
     * @var string
     * @SerializedName("type")
     */
    protected $type;

    /**
     * @var array
     * @SerializedName("roles")
     */
    protected $roles;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SignUp
     */
    public function setId(int $id): SignUp
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AccountReference
     */
    public function getAccount(): AccountReference
    {
        return $this->account;
    }

    /**
     * @param AccountReference $account
     * @return SignUp
     */
    public function setAccount(AccountReference $account): SignUp
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return SignUp
     */
    public function setType($type): SignUp
    {
        $this->type = $type;
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
     * @return SignUp
     */
    public function setRoles($roles): SignUp
    {
        $this->roles = $roles;
        return $this;
    }
}
