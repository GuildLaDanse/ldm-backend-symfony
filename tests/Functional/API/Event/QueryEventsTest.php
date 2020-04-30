<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\Event;


use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\Event\FuturePendingEventsFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class QueryEventsTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            FuturePendingEventsFixtures::class
        ));

        // When
        $this->apiGet('/api/events');

        // Then
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            FuturePendingEventsFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/events');

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(3, $response['events']);
    }

    public function testEmptyGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/events');

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(0, $response['events']);
    }
}