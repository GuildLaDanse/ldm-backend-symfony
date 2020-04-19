<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\Comments;


use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\Comments\CommentGroupFixtures;
use App\Tests\Functional\API\AbstractWebTestCase;
use Faker\Provider\Lorem;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class UpdateCommentTest extends AbstractWebTestCase
{
    use FixturesTrait;

    /** @var KernelBrowser|null  */
    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedPost(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => 'some message'
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testNonExistingComment(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->request('POST', '/api/comments/comments/10fa11c796543a2151161f5d99b05c11',
            [], [], [],
            json_encode([
                'message' => 'some message'
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateAsPoster(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => 'some message'
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateAsSomeoneElse(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT2);

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => 'some message'
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateEmptyMessage(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => ''
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateNullMessage(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => null
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateTooLargeMessage(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn($this->client, AccountFixtures::EMAIL_ACCOUNT1);

        $this->client->request('POST', '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [], [], [],
            json_encode([
                'message' => Lorem::lexify(str_repeat('?', 300))
            ], JSON_THROW_ON_ERROR, 512));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}