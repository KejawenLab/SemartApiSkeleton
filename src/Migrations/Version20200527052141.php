<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527052141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE core_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supervisor_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(180) NOT NULL, full_name VARCHAR(55) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BF76157CF85E0677 (username), UNIQUE INDEX UNIQ_BF76157CE7927C74 (email), INDEX IDX_BF76157CFE54D947 (group_id), UNIQUE INDEX UNIQ_BF76157C19E9AC5F (supervisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_BF76157CFE54D947 FOREIGN KEY (group_id) REFERENCES core_group (id)');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_BF76157C19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES core_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE core_user DROP FOREIGN KEY FK_BF76157C19E9AC5F');
        $this->addSql('DROP TABLE core_user');
    }
}
