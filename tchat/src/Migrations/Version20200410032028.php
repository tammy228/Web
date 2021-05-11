<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410032028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat_room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, activity DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6495E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_to_chatrooms (user_id INT NOT NULL, chat_room_id INT NOT NULL, INDEX IDX_E5FF06B1A76ED395 (user_id), INDEX IDX_E5FF06B11819BCFA (chat_room_id), PRIMARY KEY(user_id, chat_room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_to_users (uuid VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, friend_user_id INT DEFAULT NULL, room_id INT DEFAULT NULL, message_id INT DEFAULT NULL, private INT NOT NULL, INDEX IDX_64FEBF03A76ED395 (user_id), INDEX IDX_64FEBF0393D1119E (friend_user_id), INDEX IDX_64FEBF0354177093 (room_id), INDEX IDX_64FEBF03537A1329 (message_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, chatroom_id INT DEFAULT NULL, user_id INT DEFAULT NULL, text_id INT DEFAULT NULL, image_id INT DEFAULT NULL, reader INT DEFAULT NULL, create_at DATETIME NOT NULL, INDEX IDX_B6BD307FCAF8A031 (chatroom_id), INDEX IDX_B6BD307FA76ED395 (user_id), UNIQUE INDEX UNIQ_B6BD307F698D3548 (text_id), UNIQUE INDEX UNIQ_B6BD307F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE text (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_to_chatrooms ADD CONSTRAINT FK_E5FF06B1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_to_chatrooms ADD CONSTRAINT FK_E5FF06B11819BCFA FOREIGN KEY (chat_room_id) REFERENCES chat_room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_to_users ADD CONSTRAINT FK_64FEBF03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_to_users ADD CONSTRAINT FK_64FEBF0393D1119E FOREIGN KEY (friend_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_to_users ADD CONSTRAINT FK_64FEBF0354177093 FOREIGN KEY (room_id) REFERENCES chat_room (id)');
        $this->addSql('ALTER TABLE users_to_users ADD CONSTRAINT FK_64FEBF03537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCAF8A031 FOREIGN KEY (chatroom_id) REFERENCES chat_room (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F698D3548 FOREIGN KEY (text_id) REFERENCES text (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_to_chatrooms DROP FOREIGN KEY FK_E5FF06B11819BCFA');
        $this->addSql('ALTER TABLE users_to_users DROP FOREIGN KEY FK_64FEBF0354177093');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCAF8A031');
        $this->addSql('ALTER TABLE users_to_chatrooms DROP FOREIGN KEY FK_E5FF06B1A76ED395');
        $this->addSql('ALTER TABLE users_to_users DROP FOREIGN KEY FK_64FEBF03A76ED395');
        $this->addSql('ALTER TABLE users_to_users DROP FOREIGN KEY FK_64FEBF0393D1119E');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE users_to_users DROP FOREIGN KEY FK_64FEBF03537A1329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F698D3548');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3DA5256D');
        $this->addSql('DROP TABLE chat_room');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_to_chatrooms');
        $this->addSql('DROP TABLE users_to_users');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE text');
        $this->addSql('DROP TABLE image');
    }
}
