<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\GameData;

use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\GameData\GameClassFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class GameClassTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedGet(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->apiGet('/api/gameClasses');

        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticatedGet(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            GameClassFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiGet('/api/gameClasses');

        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(4, $response);
    }
}