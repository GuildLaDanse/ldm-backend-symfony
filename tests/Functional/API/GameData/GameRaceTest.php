<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\GameData;

use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\GameData\GameFactionFixtures;
use App\Tests\DataFixtures\GameData\GameRaceFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class GameRaceTest extends ApiTestCase
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
            AccountFixtures::class
        ));

        // When
        $this->apiGet('/api/gameRaces');

        // Then
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticatedGet(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            GameFactionFixtures::class,
            GameRaceFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/gameRaces');

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(4, $response);
    }
}