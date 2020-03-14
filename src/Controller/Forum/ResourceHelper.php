<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Controller\Forum;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResourceHelper
 * @package LaDanse\ForumBundle\Controller
 */
class ResourceHelper
{
    /**
     * @param Request $request
     * @param int $httpStatusCode
     * @param string $errorMessage
     * @param array $headers
     *
     * @return JsonResponse
     */
    public static function createErrorResponse(
        Request $request,
        int $httpStatusCode,
        string $errorMessage,
        array $headers = []): JsonResponse
    {
        $jsonObject = (object)[
                "errorId"      => $httpStatusCode,
                "errorMessage" => $errorMessage
        ];

        $response = new JsonResponse($jsonObject, $httpStatusCode);

        foreach ($headers as $header => $value)
        {
            $response->headers->set($header, $value);
        }

        ResourceHelper::addAccessControlAllowOrigin($request, $response);

        return $response;
    }

    /**
     * @return string
     */
    public static function createUUID(): string
    {
        return md5(uniqid());
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public static function addAccessControlAllowOrigin(Request $request, Response $response): void
    {
        $origin = $request->headers->get('Origin');

        if (ResourceHelper::isOriginAllowed($origin))
        {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
            $response->headers->set('Access-Control-Max-Age', '1024');
        }
    }

    /**
     * @param string $origin
     *
     * @return bool
     */
    static public function isOriginAllowed(string $origin): bool
    {
        $allowedOrigins = [
            'http://localhost:8080/',
            'http://localhost:8000/'
        ];

        foreach($allowedOrigins as $allowedOrigin)
        {
            if (ResourceHelper::startsWith($origin, $allowedOrigin))
            {
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $mainstring
     * @param string $substring
     *
     * @return bool
     */
    static public function startsWith(string $mainstring, string $substring): bool
    {
        return $substring === "" || strpos($mainstring, $substring) === 0;
    }
}
