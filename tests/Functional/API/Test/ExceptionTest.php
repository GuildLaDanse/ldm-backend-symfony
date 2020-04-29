<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\Test;


use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class ExceptionTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testServiceExceptionGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/test/throwServiceException');

        // Then
        $this->assertEquals(Response::HTTP_ALREADY_REPORTED, $this->client->getResponse()->getStatusCode());
    }

    public function testParameterTypeIntegerGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/test/throwTypeError/123');

        // Then
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testParameterTypeFloatGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/test/throwTypeError/12.3');

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testParameterTypeStringGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/test/throwTypeError/abc');

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}