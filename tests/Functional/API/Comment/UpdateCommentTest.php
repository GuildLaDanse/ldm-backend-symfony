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

class UpdateCommentTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    public function testUnauthenticatedPost(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testNonExistingComment(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/comments/10fa11c796543a2151161f5d99b05c11',
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateAsPoster(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_OK);
    }

    public function testUpdateAsSomeoneElse(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT2);

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateEmptyMessage(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => ''
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateNullMessage(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => null
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateTooLargeMessage(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/comments/' . CommentGroupFixtures::MULTI_GROUP_COMMENT1_UUID,
            [
                'message' => Lorem::lexify(str_repeat('?', 300))
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }
}