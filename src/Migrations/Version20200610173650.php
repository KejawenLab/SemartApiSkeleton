<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610173650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE core_cronjob_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_ad44f6439cd1561fec2320c9184b9439_idx (type), INDEX object_id_ad44f6439cd1561fec2320c9184b9439_idx (object_id), INDEX discriminator_ad44f6439cd1561fec2320c9184b9439_idx (discriminator), INDEX transaction_hash_ad44f6439cd1561fec2320c9184b9439_idx (transaction_hash), INDEX blame_id_ad44f6439cd1561fec2320c9184b9439_idx (blame_id), INDEX created_at_ad44f6439cd1561fec2320c9184b9439_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_menu_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (type), INDEX object_id_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (object_id), INDEX discriminator_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (discriminator), INDEX transaction_hash_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (transaction_hash), INDEX blame_id_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (blame_id), INDEX created_at_f4e4e240a25a0c8fc0cdd90e3af6b736_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_setting_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_641614354cad05a9a9fbde8144d82025_idx (type), INDEX object_id_641614354cad05a9a9fbde8144d82025_idx (object_id), INDEX discriminator_641614354cad05a9a9fbde8144d82025_idx (discriminator), INDEX transaction_hash_641614354cad05a9a9fbde8144d82025_idx (transaction_hash), INDEX blame_id_641614354cad05a9a9fbde8144d82025_idx (blame_id), INDEX created_at_641614354cad05a9a9fbde8144d82025_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_0a943a260e938f3ab643fa2cee1094a5_idx (type), INDEX object_id_0a943a260e938f3ab643fa2cee1094a5_idx (object_id), INDEX discriminator_0a943a260e938f3ab643fa2cee1094a5_idx (discriminator), INDEX transaction_hash_0a943a260e938f3ab643fa2cee1094a5_idx (transaction_hash), INDEX blame_id_0a943a260e938f3ab643fa2cee1094a5_idx (blame_id), INDEX created_at_0a943a260e938f3ab643fa2cee1094a5_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_group_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_eabd848f11c917533d8b738633249cbd_idx (type), INDEX object_id_eabd848f11c917533d8b738633249cbd_idx (object_id), INDEX discriminator_eabd848f11c917533d8b738633249cbd_idx (discriminator), INDEX transaction_hash_eabd848f11c917533d8b738633249cbd_idx (transaction_hash), INDEX blame_id_eabd848f11c917533d8b738633249cbd_idx (blame_id), INDEX created_at_eabd848f11c917533d8b738633249cbd_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_permission_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_87cb312573197644d92310cfcc36deb8_idx (type), INDEX object_id_87cb312573197644d92310cfcc36deb8_idx (object_id), INDEX discriminator_87cb312573197644d92310cfcc36deb8_idx (discriminator), INDEX transaction_hash_87cb312573197644d92310cfcc36deb8_idx (transaction_hash), INDEX blame_id_87cb312573197644d92310cfcc36deb8_idx (blame_id), INDEX created_at_87cb312573197644d92310cfcc36deb8_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_media_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, discriminator VARCHAR(255) DEFAULT NULL, transaction_hash VARCHAR(40) DEFAULT NULL, diffs JSON DEFAULT NULL, blame_id VARCHAR(255) DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_738bad3be63dc6c40f56a081da353437_idx (type), INDEX object_id_738bad3be63dc6c40f56a081da353437_idx (object_id), INDEX discriminator_738bad3be63dc6c40f56a081da353437_idx (discriminator), INDEX transaction_hash_738bad3be63dc6c40f56a081da353437_idx (transaction_hash), INDEX blame_id_738bad3be63dc6c40f56a081da353437_idx (blame_id), INDEX created_at_738bad3be63dc6c40f56a081da353437_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE core_cronjob_audit');
        $this->addSql('DROP TABLE core_menu_audit');
        $this->addSql('DROP TABLE core_setting_audit');
        $this->addSql('DROP TABLE core_user_audit');
        $this->addSql('DROP TABLE core_group_audit');
        $this->addSql('DROP TABLE core_permission_audit');
        $this->addSql('DROP TABLE core_media_audit');
    }
}
