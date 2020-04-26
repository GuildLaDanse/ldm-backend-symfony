<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures\Comment;


use App\Entity\Account\Account;
use App\Entity\Comments\Comment;
use App\Entity\Comments\CommentGroup;
use App\Tests\DataFixtures\Account\AccountFixtures;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentGroupFixtures extends Fixture
{
    public const MULTI_GROUP = 'multi_group';
    public const MULTI_GROUP_COMMENT1 = 'multi_group.comment1';
    public const MULTI_GROUP_COMMENT2 = 'multi_group.comment2';
    public const MULTI_GROUP_UUID = 'ba9eaa0804306f1443325d0faf1ed6fe';
    public const MULTI_GROUP_COMMENT1_UUID = 'b81557fd34a9c48aaff0c11e0db2bdc8';
    public const MULTI_GROUP_COMMENT2_UUID = 'c4705423d246cca58ceace7d6996e37f';

    public const SINGLE_GROUP = 'single_group';
    public const SINGLE_GROUP_COMMENT1 = 'single_group.comment1';
    public const SINGLE_GROUP_UUID = '08aff0d21726752e91dfdbc67bfd79a5';
    public const SINGLE_GROUP_COMMENT1_UUID = '0328fb4d593b77c077ca55439e7a6f0a';

    public const EMPTY_GROUP = 'empty_group';
    public const EMPTY_GROUP_UUID = 'f4e4293cc712f859e1851c5d190aa7af';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $this->createEmptyGroup($manager);
        $this->createGroupWithSingleComment($manager);
        $this->createGroupWithMultipleComments($manager);
    }

    private function createEmptyGroup(ObjectManager $manager): void
    {
        $group = $this->createCommentGroup(self::EMPTY_GROUP_UUID);
        $manager->persist($group);

        $manager->flush();

        $this->addReference(self::EMPTY_GROUP, $group);
    }

    private function createGroupWithSingleComment(ObjectManager $manager): void
    {
        $group = $this->createCommentGroup(self::SINGLE_GROUP_UUID);
        $manager->persist($group);

        $comment1 = $this->createComment(
            $group,
            self::SINGLE_GROUP_COMMENT1_UUID,
            'message1',
            $this->getAccount(AccountFixtures::ACCOUNT1)
        );
        $manager->persist($comment1);

        $manager->flush();

        $this->addReference(self::SINGLE_GROUP, $group);
        $this->addReference(self::SINGLE_GROUP_COMMENT1, $comment1);
    }

    private function createGroupWithMultipleComments(ObjectManager $manager): void
    {
        $group = $this->createCommentGroup(self::MULTI_GROUP_UUID);
        $manager->persist($group);

        $comment1 = $this->createComment(
            $group,
            self::MULTI_GROUP_COMMENT1_UUID,
            'message1',
            $this->getAccount(AccountFixtures::ACCOUNT1)
        );
        $manager->persist($comment1);

        $comment2 = $this->createComment(
            $group,
            self::MULTI_GROUP_COMMENT2_UUID,
            'message2',
            $this->getAccount(AccountFixtures::ACCOUNT2)
        );
        $manager->persist($comment2);

        $manager->flush();

        $this->addReference(self::MULTI_GROUP, $group);
        $this->addReference(self::MULTI_GROUP_COMMENT1, $comment1);
        $this->addReference(self::MULTI_GROUP_COMMENT2, $comment2);
    }

    private function createCommentGroup(string $uuid): CommentGroup
    {
        $commentGroup = new CommentGroup();

        $commentGroup
            ->setId($uuid)
            ->setCreateDate(new DateTime())
        ;

        return $commentGroup;
    }

    private function createComment(
        CommentGroup $commentGroup,
        string $uuid,
        string $message,
        Account $poster): Comment
    {
        $comment = new Comment();

        $comment
            ->setId($uuid)
            ->setGroup($commentGroup)
            ->setMessage($message)
            ->setPostDate(new DateTime())
            ->setPoster($poster)
        ;

        return $comment;
    }

    public function getAccount($reference): Account
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getReference($reference);
    }
}