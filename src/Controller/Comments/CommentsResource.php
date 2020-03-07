<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Controller\Comments;

use App\Infrastructure\Rest\AbstractRestController;
use App\Infrastructure\Rest\ResourceHelper;
use App\Infrastructure\Security\AuthenticationService;
use App\Modules\Comment\CommentDoesNotExistException;
use App\Modules\Comment\CommentGroupDoesNotExistException;
use App\Modules\Comment\CommentService;
use Psr\Log\LoggerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RS\DiExtraBundle\Annotation as DI;

/**
 * @Route("/api/comments")
 */
class CommentsResource extends AbstractRestController
{
    /**
     * @DI\Inject("monolog.logger.ladanse")
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @param Request $request
     * @param CommentService $commentService
     * @param string $groupId
     *
     * @return Response
     *
     * @Route("/groups/{groupId}", name="getCommentsInGroup", methods={"GET"})
     */
    public function getCommentsInGroupAction(Request $request, CommentService $commentService, $groupId)
    {
        try
        {
            $group = $commentService->getCommentGroup($groupId);
        }
        catch (CommentGroupDoesNotExistException $e)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                ["Allow" => "GET"]
            );
        }

        $groupMapper = new CommentGroupMapper();

        $jsonObject = $groupMapper->mapGroupAndComments($this->get('router'), $group);

        return new JsonResponse($jsonObject);
    }

    /**
     * @param Request $request
     * @param AuthenticationService $authenticationService
     * @param CommentService $commentService
     * @param string $groupId
     *
     * @return Response
     *
     * @Route("/groups/{groupId}/comments", name="createComment", methods={"POST", "PUT"})
     */
    public function createCommentAction(
        Request $request,
        AuthenticationService $authenticationService,
        CommentService $commentService,
        $groupId)
    {
        $authContext = $authenticationService->getCurrentContext();

        if (!$authContext->isAuthenticated())
        {
            $this->logger->warning(__CLASS__ . ' the user was not authenticated in createComment');

            $jsonObject = (object)[
                "status" => "must be authenticated"
            ];

            return new JsonResponse($jsonObject);
        }

        $jsonData = $request->getContent(false);

        $jsonObject = json_decode($jsonData);

        try
        {
            $commentService->createComment($groupId, $authContext->getAccount(), $jsonObject->message);
        }
        catch (CommentGroupDoesNotExistException $e)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                ["Allow" => "GET"]
            );
        }

        $jsonObject = (object)[
            "status" => "comment created in group"
        ];

        return new JsonResponse($jsonObject);
    }

    /**
     * @param Request $request
     * @param CommentService $commentService
     * @param string $commentId
     *
     * @return Response
     *
     * @Route("/comments/{commentId}", name="updateComment", methods={"POST", "PUT"})
     */
    public function updateCommentAction(
        Request $request,
        CommentService $commentService,
        $commentId)
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->get(AuthenticationService::SERVICE_NAME);
        $authContext = $authenticationService->getCurrentContext();

        $comment = null;

        try
        {
            $comment = $commentService->getComment($commentId);
        }
        catch (CommentDoesNotExistException $e)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                ["Allow" => "GET"]
            );
        }

        if (!($comment->getPoster()->getId() == $authContext->getAccount()->getId()))
        {
            return ResourceHelper::createErrorResponse(
                $request,
                Response::HTTP_FORBIDDEN,
                'Not allowed',
                ["Allow" => "GET"]
            );
        }

        $jsonData = $request->getContent(false);

        $jsonObject = json_decode($jsonData);

        try
        {
            $commentService->updateComment($commentId, $jsonObject->message);
        }
        catch (CommentDoesNotExistException $e)
        {
            return ResourceHelper::createErrorResponse(
                $request,
                Response::HTTP_NOT_FOUND,
                $e->getMessage(),
                ["Allow" => "GET"]
            );
        }

        $jsonObject = (object)[
            "status" => "OK"
        ];

        return new JsonResponse($jsonObject);
    }
}
