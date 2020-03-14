<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Controller\Forum;

use App\Infrastructure\Rest\AbstractRestController;
use App\Infrastructure\Security\AuthenticationService;
use App\Modules\Event\Forum\ForumStatsService;
use Psr\Log\LoggerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/account")
 */
class AccountResource extends AbstractRestController
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    public function  __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param AuthenticationService $authenticationService
     * @param ForumStatsService $statsService
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @Route("/unread", name="getUnreadForAccount", methods={"GET"})
     */
    public function getUnreadForAccountAction(
        AuthenticationService $authenticationService,
        ForumStatsService $statsService)
    {
        $authContext = $authenticationService->getCurrentContext();

        if (!$authContext->isAuthenticated())
        {
            $this->logger->warning(__CLASS__ . ' the user was not authenticated in getUnreadForAccount');

            $jsonObject = (object)[
                "status" => "must be authenticated"
            ];

            return new JsonResponse($jsonObject);
        }

        $account = $authContext->getAccount();

        $unreadPosts = $statsService->getUnreadPostsForAccount($account);

        $postMapper = new PostMapper();

        $jsonObject = (object)[
            "accountId"   => $account->getId(),
            "displayName" => $account->getDisplayName(),
            "unreadPosts" => $postMapper->mapPostsAndTopic($this->get('router'), $unreadPosts),
            "links"       => (object)[
                "self"  => $this->generateUrl('getUnreadForAccount', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ]
        ];

        return new JsonResponse($jsonObject);
    }
}
