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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
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
        $this->client->request('GET', '/api/events');

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
        $this->client->request('GET', '/api/events');

        // Then
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(3, $jsonResponse['events']);
    }

    public function testEmptyGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->client->request('GET', '/api/events');

        // Then
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(0, $jsonResponse['events']);
    }
}