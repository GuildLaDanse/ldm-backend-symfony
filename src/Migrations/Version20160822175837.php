<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160822175837 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Account CHANGE username username VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE username_canonical username_canonical VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE email_canonical email_canonical VARCHAR(180) NOT NULL');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Account CHANGE username username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE Account CHANGE email_canonical email_canonical VARCHAR(255) NOT NULL');
    }
}
