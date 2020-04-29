<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\Comment;


use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\Comment\CommentGroupFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Faker\Provider\Lorem;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class CreateCommentTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedCreate(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateInNonExistingGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/10fa11c796543a2151161f5d99b05c11/comments',
            [
                'message' => 'some message'
            ]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateInEmptyGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::EMPTY_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateInNonEmptyGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateEmptyComment(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => ''
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateNullComment(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => null
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateTooLargeComment(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => Lorem::lexify(str_repeat('?', 300))
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}