<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Entity\GameData;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Guild", options={"collate":"utf8mb4_0900_ai_ci", "charset":"utf8mb4"}))
 */
class Guild
{
    const REPOSITORY = 'LaDanseDomainBundle:GameData\Guild';

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var Realm $realm The realm this guild was created on
     *
     * @ORM\ManyToOne(targetEntity="Realm")
     * @ORM\JoinColumn(name="realm", referencedColumnName="id", nullable=false)
     */
    protected $realm;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Guild
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Guild
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Realm
     */
    public function getRealm(): Realm
    {
        return $this->realm;
    }

    /**
     * @param Realm $realm
     * @return Guild
     */
    public function setRealm(Realm $realm): Guild
    {
        $this->realm = $realm;
        return $this;
    }
}