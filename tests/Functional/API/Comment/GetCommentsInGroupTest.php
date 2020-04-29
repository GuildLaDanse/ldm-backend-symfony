<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\Functional\API\Comment;


use App\Tests\DataFixtures\Account\AccountFixtures;
use App\Tests\DataFixtures\Comment\CommentGroupFixtures;
use App\Tests\Functional\API\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class GetCommentsInGroupTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedExistingCommentGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testAuthenticatedNonExistingCommentGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiGet('/api/comments/groups/10fa11c796543a2151161f5d99b05c11');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testGroupWithMultipleComments(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $response = $this->responseAsObject();

        $this->assertCount(2, $response['comments']);
    }

    public function testEmptyGroup(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::EMPTY_GROUP_UUID);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $response = $this->responseAsObject();

        $this->assertCount(0, $response['comments']);
    }

    public function testGroupWithSingleComment(): void
    {
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::SINGLE_GROUP_UUID);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $response = $this->responseAsObject();

        $this->assertCount(1, $response['comments']);
    }
}