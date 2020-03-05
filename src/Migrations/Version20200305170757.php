<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305170757 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Event ADD invite_time DATETIME NOT NULL, ADD start_time DATETIME NOT NULL, ADD end_time DATETIME NOT NULL, ADD last_modified_time DATETIME NOT NULL, DROP inviteTime, DROP startTime, DROP endTime, DROP lastModifiedTime, CHANGE topicid topic_id LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE DiscordAuthCode CHANGE authcode auth_code VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE DiscordAccessToken CHANGE accesstoken access_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE ActivityQueueItem CHANGE activitytype activity_type VARCHAR(255) NOT NULL, CHANGE activityon activity_on DATETIME NOT NULL, CHANGE rawdata raw_data LONGTEXT DEFAULT NULL, CHANGE processedon processed_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE NotificationQueueItem CHANGE activitytype activity_type VARCHAR(255) NOT NULL, CHANGE activityon activity_on DATETIME NOT NULL, CHANGE rawdata raw_data LONGTEXT DEFAULT NULL, CHANGE processedon processed_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE CharacterClaim CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE GuildCharacterVersion CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_B28B6F3892FC23A8 ON Account');
        $this->addSql('DROP INDEX UNIQ_B28B6F38A0D96FBF ON Account');
        $this->addSql('DROP INDEX UNIQ_B28B6F38C05FB297 ON Account');
        $this->addSql('ALTER TABLE Account ADD external_id VARCHAR(64) NOT NULL, DROP username, DROP username_canonical, DROP email_canonical, DROP enabled, DROP salt, DROP password, DROP last_login, DROP confirmation_token, DROP password_requested_at, DROP roles, CHANGE email email VARCHAR(128) NOT NULL, CHANGE displayname display_name VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE CharacterClaimVersion CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE InGuild CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE GuildCharacter CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE FeatureUse CHANGE usedon used_on DATETIME NOT NULL, CHANGE rawdata raw_data LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE GameFaction CHANGE armoryid armory_id INT NOT NULL');
        $this->addSql('ALTER TABLE GameClass CHANGE armoryid armory_id INT NOT NULL');
        $this->addSql('ALTER TABLE GameRace CHANGE armoryid armory_id INT NOT NULL');
        $this->addSql('ALTER TABLE CalendarExport ADD export_new TINYINT(1) NOT NULL, ADD export_absence TINYINT(1) NOT NULL, DROP exportNew, DROP exportAbsence');
        $this->addSql('ALTER TABLE MailSend CHANGE sendon send_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE PlaysRole CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Feedback CHANGE postedon posted_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE SocialConnect ADD resource_id VARCHAR(255) NOT NULL, ADD access_token VARCHAR(255) NOT NULL, DROP resourceId, DROP accessToken, CHANGE refreshtoken refresh_token VARCHAR(255) DEFAULT NULL, CHANGE connecttime connect_time DATETIME NOT NULL, CHANGE lastrefreshtime last_refresh_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE TrackedBy CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE CharacterSyncSession CHANGE fromtime from_time DATETIME NOT NULL, CHANGE endtime end_time DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Account ADD username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD username_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD email_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD enabled TINYINT(1) NOT NULL, ADD salt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD password_requested_at DATETIME DEFAULT NULL, ADD roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', DROP external_id, CHANGE email email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE display_name displayName VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B28B6F3892FC23A8 ON Account (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B28B6F38A0D96FBF ON Account (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B28B6F38C05FB297 ON Account (confirmation_token)');
        $this->addSql('ALTER TABLE ActivityQueueItem CHANGE activity_type activityType VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE activity_on activityOn DATETIME NOT NULL, CHANGE raw_data rawData LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE processed_on processedOn DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE CalendarExport ADD exportNew TINYINT(1) NOT NULL, ADD exportAbsence TINYINT(1) NOT NULL, DROP export_new, DROP export_absence');
        $this->addSql('ALTER TABLE CharacterClaim CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE CharacterClaimVersion CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE CharacterSyncSession CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE DiscordAccessToken CHANGE access_token accessToken VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE DiscordAuthCode CHANGE auth_code authCode VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Event ADD startTime DATETIME NOT NULL, ADD endTime DATETIME NOT NULL, ADD lastModifiedTime DATETIME NOT NULL, DROP invite_time, DROP start_time, DROP end_time, CHANGE last_modified_time inviteTime DATETIME NOT NULL, CHANGE topic_id topicId LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FeatureUse CHANGE used_on usedOn DATETIME NOT NULL, CHANGE raw_data rawData LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Feedback CHANGE posted_on postedOn DATETIME NOT NULL');
        $this->addSql('ALTER TABLE GameClass CHANGE armory_id armoryId INT NOT NULL');
        $this->addSql('ALTER TABLE GameFaction CHANGE armory_id armoryId INT NOT NULL');
        $this->addSql('ALTER TABLE GameRace CHANGE armory_id armoryId INT NOT NULL');
        $this->addSql('ALTER TABLE GuildCharacter CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE GuildCharacterVersion CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE InGuild CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE MailSend CHANGE send_on sendOn DATETIME NOT NULL');
        $this->addSql('ALTER TABLE NotificationQueueItem CHANGE activity_type activityType VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE activity_on activityOn DATETIME NOT NULL, CHANGE raw_data rawData LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE processed_on processedOn DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE PlaysRole CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE SocialConnect ADD resourceId VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD accessToken VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP resource_id, DROP access_token, CHANGE refresh_token refreshToken VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE connect_time connectTime DATETIME NOT NULL, CHANGE last_refresh_time lastRefreshTime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE TrackedBy CHANGE from_time fromTime DATETIME NOT NULL, CHANGE end_time endTime DATETIME DEFAULT NULL');
    }
}
