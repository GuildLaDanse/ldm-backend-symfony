<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Modules\Common;

use JMS\Serializer\Annotation\SerializedName;

class CommentGroupReference
{
    /**
     * @SerializedName("id")
     *
     * @var int
     */
    protected int $id;

    /**
     * AccountReference constructor.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}