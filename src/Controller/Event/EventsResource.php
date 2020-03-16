<?php /** @noinspection PhpRedundantCatchClauseInspection */
declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Controller\Event;

use App\Infrastructure\Modules\ServiceException;
use App\Infrastructure\Rest\AbstractRestController;
use App\Infrastructure\Rest\ResourceHelper;
use App\Modules\Event\DTO\PostEvent;
use App\Modules\Event\DTO\PostSignUp;
use App\Modules\Event\DTO\PutEvent;
use App\Modules\Event\DTO\PutEventState;
use App\Modules\Event\DTO\PutSignUp;
use App\Modules\Event\EventService;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class EventsResource extends AbstractRestController
{
    /**
     * @param Request $request
     * @param EventService $eventService
     *
     * @return Response
     *
     * @Route("/", name="queryEvents", options = { "expose" = true }, methods={"GET", "HEAD"})
     */
    public function queryEventsAction(Request $request, EventService $eventService): Response
    {
        try
        {
            /** @var DateTime $startOnDate */
            $startOnDate = $this->getStartOnDate($request->query->get('startOn'));

            $eventPage = $eventService->getAllEventsPaged($startOnDate);

            return new JsonResponse($eventPage);
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param int $eventId
     *
     * @return Response
     *
     * @Route("/{eventId}", name="queryEventById", options = { "expose" = true }, methods={"GET", "HEAD"})
     */
    public function queryEventByIdAction(Request $request, EventService $eventService, $eventId): Response
    {
        try
        {
            $event = $eventService->getEventById($eventId);

            return new JsonResponse($event);
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     *
     * @return Response
     *
     * @Route("/", name="postEvent", options = { "expose" = true }, methods={"POST"})
     */
    public function postEventAction(Request $request, EventService $eventService)
    {
        try
        {
            /** @var PostEvent $postEventDto */
            $postEventDto = $this->getDtoFromContent($request, PostEvent::class);

            $eventDto = $eventService->postEvent($postEventDto);

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     *
     * @return Response
     *
     * @Route("/{eventId}", name="putEvent", options = { "expose" = true }, methods={"PUT"})
     */
    public function putEventAction(Request $request, EventService $eventService, $eventId)
    {
        try
        {
            /** @var PutEvent $putEventDto */
            $putEventDto = $this->getDtoFromContent($request, PutEvent::class);

            $eventDto = $eventService->putEvent(intval($eventId), $putEventDto);

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     *
     * @return Response
     *
     * @Route("/{eventId}/state", name="putEventState", options = { "expose" = true }, methods={"PUT"})
     */
    public function putEventStateAction(Request $request, EventService $eventService, $eventId)
    {
        try
        {
            /** @var PutEventState $putEventStateDto */
            $putEventStateDto = $this->getDtoFromContent($request, PutEventState::class);

            $eventDto = $eventService->putEventState(intval($eventId), $putEventStateDto);

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     *
     * @return Response
     *
     * @Route("/{eventId}", name="deleteEvent", options = { "expose" = true }, methods={"DELETE"})
     */
    public function deleteEventAction(Request $request, EventService $eventService, $eventId): Response
    {
        try
        {
            $eventService->deleteEvent(intval($eventId));

            return new Response();
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     *
     * @return Response
     *
     * @Route("/{eventId}/signUps", name="postSignUp", options = { "expose" = true }, methods={"POST"})
     */
    public function postSignUpAction(Request $request, EventService $eventService, $eventId)
    {
        try
        {
            /** @var PostSignUp $postSignUpDto */
            $postSignUpDto = $this->getDtoFromContent($request, PostSignUp::class);

            $eventDto = $eventService->postSignUp(intval($eventId), $postSignUpDto);

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     * @param string $signUpId
     *
     * @return Response
     *
     * @Route("/{eventId}/signUps/{signUpId}", name="putSignUp", options = { "expose" = true }, methods={"PUT"})
     */
    public function putSignUpAction(Request $request, EventService $eventService, $eventId, $signUpId): Response
    {
        try
        {
            /** @var PutSignUp $putSignUpDto */
            $putSignUpDto = $this->getDtoFromContent($request, PutSignUp::class);

            $eventDto = $eventService->putSignUp(intval($eventId), intval($signUpId), $putSignUpDto);

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param string $eventId
     * @param string $signUpId
     *
     * @return Response
     *
     * @Route("/{eventId}/signUps/{signUpId}", name="deleteSignUp", options = { "expose" = true }, methods={"DELETE"})
     */
    public function deleteSignUpAction(Request $request, EventService $eventService, $eventId, $signUpId): Response
    {
        try
        {
            $eventDto = $eventService->deleteSignUp(intval($eventId), intval($signUpId));

            return new JsonResponse(ResourceHelper::object($eventDto));
        }
        catch(ServiceException $serviceException)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                $serviceException->getCode(),
                $serviceException->getMessage()
            );
        }
    }

    private function getStartOnDate($pStartOnDate)
    {
        if ($pStartOnDate == null)
        {
            return new DateTime();
        }

        return DateTime::createFromFormat('Ymd', $pStartOnDate);
    }
}
