<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710034122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, en_title VARCHAR(255) NOT NULL, zh_title VARCHAR(255) NOT NULL, en_content LONGTEXT NOT NULL, zh_content LONGTEXT NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1DD39950D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production_range (id INT AUTO_INCREMENT NOT NULL, en_name VARCHAR(255) NOT NULL, zh_name VARCHAR(255) NOT NULL, en_description VARCHAR(255) NOT NULL, zh_description VARCHAR(255) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', show_case TINYINT(1) NOT NULL, sort INT NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_89869E7FD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banner (id INT AUTO_INCREMENT NOT NULL, en_title VARCHAR(255) NOT NULL, zh_title VARCHAR(255) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, owner VARCHAR(255) DEFAULT NULL, uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6F9DB8E7D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, role_codes INT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, owner VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, keyword VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, shipping_standard VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE production_range');
        $this->addSql('DROP TABLE banner');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE config');
    }
}
