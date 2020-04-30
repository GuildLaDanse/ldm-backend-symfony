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
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateInNonExistingGroup(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/10fa11c796543a2151161f5d99b05c11/comments',
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function testCreateInEmptyGroup(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::EMPTY_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_OK);
    }

    public function testCreateInNonEmptyGroup(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => 'some message'
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_OK);
    }

    public function testCreateEmptyComment(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => ''
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateNullComment(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => null
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateTooLargeComment(): void
    {
        // Given
        $this->loadFixtures(array(
            AccountFixtures::class,
            CommentGroupFixtures::class
        ));

        $this->logIn(AccountFixtures::EMAIL_ACCOUNT1);

        // When
        $this->apiPost(
            '/api/comments/groups/' . CommentGroupFixtures::MULTI_GROUP_UUID . '/comments',
            [
                'message' => Lorem::lexify(str_repeat('?', 300))
            ]
        );

        // Then
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST);
    }
}