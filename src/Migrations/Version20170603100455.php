<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace DoctrineMigrations;

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170603100455 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

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
        $this->addSql('SELECT 1');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     * @throws ConnectionException
     * @throws Exception
     */
    public function postUp(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /** @var Connection $conn */
        $conn = $this->container->get('database_connection');

        $conn->beginTransaction();

        // find all GuildCharacters that have an endTime set

        $stmt = $conn->query("SELECT e.id, e.inviteTime, e.startTime, e.endTime FROM Event as e");

        $realmServerTimeZone = new DateTimeZone('Europe/Paris');
        $utcTimeZone = new DateTimeZone('UTC');

        $eventTimes = [];

        $row = $stmt->fetch();
        while ($row)
        {
            $eventTimes[] = [
                'id'         => $row['id'],
                'inviteTime' => $row['inviteTime'],
                'startTime'  => $row['startTime'],
                'endTime'    => $row['endTime']
            ];

            $row = $stmt->fetch();
        }

        $stmt->closeCursor();

        foreach($eventTimes as $eventTime)
        {
            $originalInviteTime = new DateTime($eventTime['inviteTime'], $realmServerTimeZone);
            $newInviteTime = (clone $originalInviteTime)->setTimezone($utcTimeZone);

            $originalStartTime = new DateTime($eventTime['startTime'], $realmServerTimeZone);
            $newStartTime = (clone $originalStartTime)->setTimezone($utcTimeZone);

            $originalEndTime = new DateTime($eventTime['endTime'], $realmServerTimeZone);
            $newEndTime = (clone $originalEndTime)->setTimezone($utcTimeZone);

            $updateStmt = $conn->prepare("UPDATE Event SET inviteTime = :newInviteTime, startTime = :newStartTime, endTime = :newEndTime WHERE id = :id");
            $updateStmt->bindValue("id", $eventTime['id']);
            $updateStmt->bindValue("newInviteTime", $newInviteTime->format("Y-m-d H:i:s"));
            $updateStmt->bindValue("newStartTime", $newStartTime->format("Y-m-d H:i:s"));
            $updateStmt->bindValue("newEndTime", $newEndTime->format("Y-m-d H:i:s"));

            $updateStmt->execute();
        }

        $conn->commit();
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
