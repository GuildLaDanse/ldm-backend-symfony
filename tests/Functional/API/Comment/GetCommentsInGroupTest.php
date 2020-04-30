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
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        // When
        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID);

        // Then
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticatedNonExistingCommentGroup(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/comments/groups/10fa11c796543a2151161f5d99b05c11');

        // Then
        $this->assertStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function testGroupWithMultipleComments(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID);

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(2, $response['comments']);
    }

    public function testEmptyGroup(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::EMPTY_GROUP_UUID);

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(0, $response['comments']);
    }

    public function testGroupWithSingleComment(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiGet('/api/comments/groups/' . CommentGroupFixtures::SINGLE_GROUP_UUID);

        // Then
        $this->assertStatusCode(Response::HTTP_OK);

        $response = $this->responseAsObject();

        $this->assertCount(1, $response['comments']);
    }
}