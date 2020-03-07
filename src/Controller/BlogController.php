<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Account;
use App\Modules\Comment\CommentGroupDoesNotExistException;
use App\Modules\Comment\CommentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/api/public/hello", name="publicHello")
     *
     * @return JsonResponse
     */
    public function publicIndexAction()
    {
        /** @var Account */
        $user = $this->getUser();

        return new JsonResponse(array(
            'ping' => 'pong',
            'user_type' => get_class($user),
            'username' => $user->getUsername(),
            'displayName' => $user->getDisplayName()
        ));
    }

    /**
     * @Route("/api/private/hello", name="privateHello")
     *
     * @param CommentService $commentService
     *
     * @return JsonResponse
     *
     * @throws CommentGroupDoesNotExistException
     * @throws Exception
     */
    public function privateIndexAction(CommentService $commentService)
    {
        /** @var Account */
        $user = $this->getUser();

        $groupId = $commentService->createCommentGroup();

        $commentService->createComment($groupId, $user, "This is a comment");

        return new JsonResponse(array(
            'ping' => 'pong',
            'user_type' => get_class($user),
            'username' => $user->getUsername(),
            'displayName' => $user->getDisplayName()
        ));
    }
}
