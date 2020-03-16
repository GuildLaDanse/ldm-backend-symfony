<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace LaDanse\ServicesBundle\Service\Event\Command;

use App\Modules\Event\DTO as EventDTO;

class PostEventCommand
{
    /**
     * @var EventDTO\PostEvent
     */
    private EventDTO\PostEvent $postEventDto;


    public function __construct(EventDTO\PostEvent $postEventDto)
    {
        $this->postEventDto = $postEventDto;
    }

    /**
     * @return EventDTO\PostEvent
     */
    public function getPostEventDto(): EventDTO\PostEvent
    {
        return $this->postEventDto;
    }
}