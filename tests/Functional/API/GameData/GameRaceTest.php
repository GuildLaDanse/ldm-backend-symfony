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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class GameRaceTest extends ApiTestCase
{
    use FixturesTrait;

    /** @var KernelBrowser|null  */
    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testUnauthenticatedGet(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->client->followRedirects(true);

        $this->client->request('GET', '/api/gameRaces');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAuthenticatedGet(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            GameFactionFixtures::class,
            GameRaceFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->followRedirects(true);

        $this->client->request('GET', '/api/gameRaces');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(4, $jsonResponse);
    }
}