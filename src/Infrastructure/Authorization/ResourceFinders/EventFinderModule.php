<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Authorization\ResourceFinders;

use App\Infrastructure\Modules\LaDanseService;
use Monolog\Logger;
use RS\DiExtraBundle\Annotation as DI;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @DI\Service(EventFinderModule::SERVICE_NAME, public=true)
 */
class EventFinderModule extends LaDanseService
{
    const SERVICE_NAME = 'LaDanse.EventFinderModule';

    /**
     * @var $logger Logger
     * @DI\Inject("monolog.logger.ladanse")
     */
    public $logger;

    /**
     * @param ContainerInterface $container
     *
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /** @noinspection PhpUnusedParameterInspection */
    function findResourceById($resourceId)
    {
        return null;

        // /** @var EventService $eventService */
        //$eventService = $this->get(EventService::SERVICE_NAME);

        //return $eventService->getEventById($resourceId);
    }
}