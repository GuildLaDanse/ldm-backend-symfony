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
class Version20180131160514 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO GameRace(id, armoryId, faction, name) VALUES ("2a8040ac-06a0-11e8-92df-09e8707b16fc", 27, "e2308034-6c7f-11e6-94f1-b39df80631e5", "Nightborne")');
        $this->addSql('INSERT INTO GameRace(id, armoryId, faction, name) VALUES ("2a830ee0-06a0-11e8-92df-09e8707b16fc", 28, "e2308034-6c7f-11e6-94f1-b39df80631e5", "Highmountain Tauren")');
        $this->addSql('INSERT INTO GameRace(id, armoryId, faction, name) VALUES ("2a861838-06a0-11e8-92df-09e8707b16fc", 29, "d784ce10-6c7f-11e6-94f1-b39df80631e5", "Void Elf")');
        $this->addSql('INSERT INTO GameRace(id, armoryId, faction, name) VALUES ("2a88fa12-06a0-11e8-92df-09e8707b16fc", 30, "d784ce10-6c7f-11e6-94f1-b39df80631e5", "Lightforged Draenei")');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM GameClass WHERE id="2a8040ac-06a0-11e8-92df-09e8707b16fc"');
        $this->addSql('DELETE FROM GameClass WHERE id="2a830ee0-06a0-11e8-92df-09e8707b16fc"');
        $this->addSql('DELETE FROM GameClass WHERE id="2a861838-06a0-11e8-92df-09e8707b16fc"');
        $this->addSql('DELETE FROM GameClass WHERE id="2a88fa12-06a0-11e8-92df-09e8707b16fc"');
    }
}
