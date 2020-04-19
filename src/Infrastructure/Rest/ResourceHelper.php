<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Rest;

/**
 * Class ResourceHelper
 * @package LaDanse\ForumBundle\Controller
 */
class ResourceHelper
{
    /**
     * @param $request
     * @param $httpStatusCode
     * @param $errorMessage
     * @param array $headers
     *
     * @return JsonSerializedResponse
     */
    public static function createErrorResponse($request, $httpStatusCode, $errorMessage, $headers = []): JsonSerializedResponse
    {
        $errorResponse = new ErrorResponse();
        $errorResponse
            ->setErrorCode($httpStatusCode)
            ->setErrorMessage($errorMessage);

        $response = new JsonSerializedResponse($errorResponse, $httpStatusCode);

        foreach ($headers as $header => $value)
        {
            $response->headers->set($header, $value);
        }

        self::addAccessControlAllowOrigin($request, $response);

        return $response;
    }

    /**
     * @param $request
     * @param $response
     */
    public static function addAccessControlAllowOrigin($request, $response): void
    {
        $origin = $request->headers->get('Origin');

        if ($origin === null)
        {
            return;
        }

        if (self::isOriginAllowed($origin))
        {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
            $response->headers->set('Access-Control-Max-Age', '1024');
        }
    }

    /**
     * @param $origin
     *
     * @return bool
     */
    public static function isOriginAllowed($origin): bool
    {
        $allowedOrigins = [
            'http://localhost:8080/',
            'http://localhost:8000/'
        ];

        foreach($allowedOrigins as $allowedOrigin)
        {
            if (self::startsWith($origin, $allowedOrigin))
            {
                return false;
            }
        }

        return false;
    }

    /**
     * @param $mainstring
     * @param $substring
     *
     * @return bool
     */
    public static function startsWith($mainstring, $substring): bool
    {
        return $substring === '' || strpos($mainstring, $substring) === 0;
    }

    public static function object($object)
    {
        if ($object !== null)
        {
            return $object;
        }

        return (object)[];
    }

    public static function array($array): ?array
    {
        if ($array !== null)
        {
            return $array;
        }

        return [];
    }
}
