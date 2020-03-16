<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161107155733 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // it all happens in postUp as we can't just change the schema
        // we keep a dummy SQL statement here to avoid warnings
        $this->addSql(
            'CREATE TABLE CharacterClaimVersion (' .
                'id INT AUTO_INCREMENT NOT NULL, ' .
                'comment TEXT DEFAULT NULL, ' .
                'raider TINYINT(1) NOT NULL, ' .
                'fromTime DATETIME NOT NULL, ' .
                'endTime DATETIME DEFAULT NULL, ' .
                'claimId INT NOT NULL, ' .
                'INDEX IDX_C33F42E09113A92D (claimId), ' .
                'PRIMARY KEY(id)) ' .
                'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ' .
                'ENGINE = InnoDB');
        $this->addSql(
            'ALTER TABLE CharacterClaimVersion ' .
                'ADD CONSTRAINT FK_C33F42E09113A92D ' .
                'FOREIGN KEY (claimId) REFERENCES CharacterClaim (id)');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function postUp(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /** @var Connection $conn */
        $conn = $this->container->get('database_connection');

        // find all GuildCharacters that have an endTime set

        $stmt = $conn->query(
            "SELECT cc.id, cc.fromTime, cc.endTime, cc.comment, cc.raider " .
            "FROM CharacterClaim as cc "
        );

        $row = $stmt->fetch();
        while ($row)
        {
            $claimId  = $row['id'];
            $fromTime = $row['fromTime'];
            $endTime  = $row['endTime'];
            $comment  = $row['comment'];
            $raider   = $row['raider'];

            $insertStmt = $conn->prepare(
                "INSERT INTO CharacterClaimVersion " .
                    "(claimId, fromTime, endTime, comment, raider) " .
                    "VALUES(:claimId, :fromTime, :endTime, :comment, :raider)"
                );
            $insertStmt->bindValue("claimId", $claimId);
            $insertStmt->bindValue("fromTime", $fromTime);
            $insertStmt->bindValue("endTime", $endTime);
            $insertStmt->bindValue("comment", $comment);
            $insertStmt->bindValue("raider", $raider);
            $insertStmt->execute();

            $row = $stmt->fetch();
        }

        $stmt->closeCursor();

        $alterStmt = $conn->prepare("ALTER TABLE CharacterClaim DROP comment, DROP raider");
        $alterStmt->execute();
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        throw new DBALException("'down' migration is not support for this migration");
    }
}
