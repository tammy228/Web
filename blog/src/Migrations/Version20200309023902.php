<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309023902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo CHANGE offline_at offline_at DATE NOT NULL');
        $this->addSql('ALTER TABLE album CHANGE offline_at offline_at DATE NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE offline_at offline_at DATE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE album CHANGE offline_at offline_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE offline_at offline_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE photo CHANGE offline_at offline_at DATETIME NOT NULL');
    }
}
