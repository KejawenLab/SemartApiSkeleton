<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200530095533 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE core_menu DROP INDEX UNIQ_4FE0F9A6727ACA70, ADD INDEX IDX_4FE0F9A6727ACA70 (parent_id)');
        $this->addSql('ALTER TABLE core_user DROP INDEX UNIQ_BF76157C19E9AC5F, ADD INDEX IDX_BF76157C19E9AC5F (supervisor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE core_menu DROP INDEX IDX_4FE0F9A6727ACA70, ADD UNIQUE INDEX UNIQ_4FE0F9A6727ACA70 (parent_id)');
        $this->addSql('ALTER TABLE core_user DROP INDEX IDX_BF76157C19E9AC5F, ADD UNIQUE INDEX UNIQ_BF76157C19E9AC5F (supervisor_id)');
    }
}
