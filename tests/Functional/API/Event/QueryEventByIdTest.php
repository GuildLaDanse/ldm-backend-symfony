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

class QueryEventByIdTest extends ApiTestCase
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
        $this->client->request('GET', '/api/events/' . FuturePendingEventsFixtures::PENDING_ID);

        // Then
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
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
        $this->client->request('GET', '/api/events/' . FuturePendingEventsFixtures::PENDING_ID);

        // Then
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUnexistingGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            FuturePendingEventsFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/events/9876');

        // Then
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testMalformedIdGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            FuturePendingEventsFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/events/abc');

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}