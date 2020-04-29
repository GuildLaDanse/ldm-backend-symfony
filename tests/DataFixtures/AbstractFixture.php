<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Tests\DataFixtures;


use App\Entity\Account\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractFixture extends Fixture
{
    protected function getAccount($reference): Account
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getReference($reference);
    }

    protected function enableAssignedId(ObjectManager $manager, $entity): void
    {
        $className = $manager->getClassMetadata(get_class($entity))->name;

        $metadata = $manager->getClassMetadata($className);

        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);
    }
}