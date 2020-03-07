<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Rest;

use App\Entity\Account;
use App\Infrastructure\Authorization\AuthorizationService;
use App\Infrastructure\Authorization\CannotEvaluateException;
use App\Infrastructure\Authorization\ResourceReference;
use App\Infrastructure\Authorization\SubjectReference;
use App\Infrastructure\Modules\ServiceException;
use App\Infrastructure\Security\AuthenticationService;
use Exception;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * Class LaDanseController
 *
 * @package LaDanse\RestBundle\Common
 */
class AbstractRestController extends AbstractController
{
    /**
     * Returns true if the current request is authenticated, false otherwise
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->get(AuthenticationService::SERVICE_NAME);

        $authContext = $authenticationService->getCurrentContext();

        return $authContext->isAuthenticated();
    }

    /**
     * Returns the account that is currently logged in. When not authenticated, returns null.
     *
     * @return Account
     */
    protected function getAccount()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->get(AuthenticationService::SERVICE_NAME);

        if ($this->isAuthenticated())
        {
            return $authenticationService->getCurrentContext()->getAccount();
        }

        return null;
    }

    /**
     * @param SubjectReference $subject
     * @param string $action
     * @param ResourceReference $resource
     *
     * @return bool
     *
     * @throws CannotEvaluateException
     */
    protected function isAuthorized(SubjectReference $subject, $action, ResourceReference $resource)
    {
        /** @var AuthorizationService $authzService */
        $authzService = $this->get(AuthorizationService::SERVICE_NAME);

        return $authzService->evaluate($subject, $action, $resource);
    }

    /** @noinspection PhpUnusedParameterInspection */
    protected function hasFeatureToggled($featureName, $default = false)
    {
        if (!$this->isAuthenticated())
        {
            return $default;
        }

        //$account = $this->getAccount();

        // /** @var FeatureToggleService $featureToggleService */
        // $featureToggleService = $this->get(FeatureToggleService::SERVICE_NAME);

        // return $featureToggleService->hasAccountFeatureToggled($account, $featureName, $default);

        throw new NotImplementedException("AbstractRestController::hasFeatureToggled is not yet implemented");
    }

    /**
     * @param Request $request
     * @param string $dtoClass
     *
     * @return object
     *
     * @throws ServiceException
     */
    protected function getDtoFromContent(Request $request, string $dtoClass)
    {
        $serializer = SerializerBuilder::create()->build();

        $jsonDto = null;

        try
        {
            $jsonDto = $serializer->deserialize(
                $request->getContent(),
                $dtoClass,
                'json'
            );
        }
        catch (Exception $exception)
        {
            throw new ServiceException($exception->getMessage(), 400);
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($jsonDto);

        if (count($errors) > 0)
        {
            $errorsString = (string)$errors;

            throw new ServiceException($errorsString, 400);
        }
        else
        {
            return $jsonDto;
        }
    }
}
