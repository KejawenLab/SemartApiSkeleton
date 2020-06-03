<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603092341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE core_cronjob (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, command VARCHAR(255) NOT NULL, schedule VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, symfony_command TINYINT(1) NOT NULL, running TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_cronjob_report (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', cron_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', run_at DATETIME NOT NULL, runtime DOUBLE PRECISION NOT NULL, output LONGTEXT NOT NULL, exit_code SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2F368F4138435942 (cron_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_menu (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', parent_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(27) NOT NULL, name VARCHAR(255) NOT NULL, sort_order INT NOT NULL, route_name VARCHAR(255) NOT NULL, extra VARCHAR(255) DEFAULT NULL, showable TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4FE0F9A6727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_setting (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', parameter VARCHAR(27) NOT NULL, value LONGTEXT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supervisor_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(180) NOT NULL, full_name VARCHAR(55) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, device_id VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BF76157CF85E0677 (username), UNIQUE INDEX UNIQ_BF76157CE7927C74 (email), INDEX IDX_BF76157CFE54D947 (group_id), INDEX IDX_BF76157C19E9AC5F (supervisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(7) NOT NULL, name VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_permission (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', menu_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', addable TINYINT(1) NOT NULL, editable TINYINT(1) NOT NULL, viewable TINYINT(1) NOT NULL, deletable TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DC05164BFE54D947 (group_id), INDEX IDX_DC05164BCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_media (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', file_name VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_cronjob_report ADD CONSTRAINT FK_2F368F4138435942 FOREIGN KEY (cron_id) REFERENCES core_cronjob (id)');
        $this->addSql('ALTER TABLE core_menu ADD CONSTRAINT FK_4FE0F9A6727ACA70 FOREIGN KEY (parent_id) REFERENCES core_menu (id)');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_BF76157CFE54D947 FOREIGN KEY (group_id) REFERENCES core_group (id)');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_BF76157C19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_permission ADD CONSTRAINT FK_DC05164BFE54D947 FOREIGN KEY (group_id) REFERENCES core_group (id)');
        $this->addSql('ALTER TABLE core_permission ADD CONSTRAINT FK_DC05164BCCD7E912 FOREIGN KEY (menu_id) REFERENCES core_menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE core_cronjob_report DROP FOREIGN KEY FK_2F368F4138435942');
        $this->addSql('ALTER TABLE core_menu DROP FOREIGN KEY FK_4FE0F9A6727ACA70');
        $this->addSql('ALTER TABLE core_permission DROP FOREIGN KEY FK_DC05164BCCD7E912');
        $this->addSql('ALTER TABLE core_user DROP FOREIGN KEY FK_BF76157C19E9AC5F');
        $this->addSql('ALTER TABLE core_user DROP FOREIGN KEY FK_BF76157CFE54D947');
        $this->addSql('ALTER TABLE core_permission DROP FOREIGN KEY FK_DC05164BFE54D947');
        $this->addSql('DROP TABLE core_cronjob');
        $this->addSql('DROP TABLE core_cronjob_report');
        $this->addSql('DROP TABLE core_menu');
        $this->addSql('DROP TABLE core_setting');
        $this->addSql('DROP TABLE core_user');
        $this->addSql('DROP TABLE core_group');
        $this->addSql('DROP TABLE core_permission');
        $this->addSql('DROP TABLE core_media');
    }
}
