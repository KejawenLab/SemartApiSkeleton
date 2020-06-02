<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602082851 extends AbstractMigration
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
        $this->addSql('ALTER TABLE core_cronjob_report ADD CONSTRAINT FK_2F368F4138435942 FOREIGN KEY (cron_id) REFERENCES core_cronjob (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE core_cronjob_report DROP FOREIGN KEY FK_2F368F4138435942');
        $this->addSql('DROP TABLE core_cronjob');
        $this->addSql('DROP TABLE core_cronjob_report');
    }
}
