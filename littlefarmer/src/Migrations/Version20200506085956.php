<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200506085956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, en_title VARCHAR(255) DEFAULT NULL, zh_title VARCHAR(255) NOT NULL, en_content TINYTEXT DEFAULT NULL, zh_content TINYTEXT NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1DD39950D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C1D17F50A6 (uuid), INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BA388B7D17F50A6 (uuid), UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, zh_name VARCHAR(255) NOT NULL, en_name VARCHAR(255) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', zh_description LONGTEXT NOT NULL, en_description LONGTEXT NOT NULL, price INT NOT NULL, stock INT NOT NULL, origin VARCHAR(255) DEFAULT NULL, qrcode LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', group_buy TINYINT(1) NOT NULL, on_sale TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, detail VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD17F50A6 (uuid), INDEX IDX_D34A04ADA76ED395 (user_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_email_verify_token (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, expire_at DATETIME NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_to_user_order (user_order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_EA35964F6D128938 (user_order_id), INDEX IDX_EA35964F4584665A (product_id), PRIMARY KEY(user_order_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, email_validated TINYINT(1) DEFAULT \'0\' NOT NULL, email_validate_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, role_codes INT NOT NULL, password VARCHAR(255) NOT NULL, hash_key VARCHAR(255) DEFAULT NULL, hash_iv VARCHAR(255) DEFAULT NULL, merchant_id VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, sexual VARCHAR(255) DEFAULT NULL, deleted INT NOT NULL, applied INT NOT NULL, instruction VARCHAR(255) DEFAULT NULL, farm VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D64976F5C865 (google_id), UNIQUE INDEX UNIQ_8D93D6499BE8FD98 (facebook_id), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_to_carts (cart_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_503D6B2F1AD5CDBF (cart_id), INDEX IDX_503D6B2F4584665A (product_id), PRIMARY KEY(cart_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, farmer_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, status INT NOT NULL, delivery VARCHAR(255) NOT NULL, remark VARCHAR(255) NOT NULL, payment VARCHAR(255) NOT NULL, merchant_trade_no VARCHAR(255) NOT NULL, recipient_name VARCHAR(255) NOT NULL, recipient_mobile VARCHAR(255) NOT NULL, recipient_email VARCHAR(255) NOT NULL, recipient_address VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_17EB68C0D17F50A6 (uuid), INDEX IDX_17EB68C0A76ED395 (user_id), INDEX IDX_17EB68C013481D2B (farmer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_to_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, farmer_id INT DEFAULT NULL, INDEX IDX_3270D98CA76ED395 (user_id), INDEX IDX_3270D98C13481D2B (farmer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forget_password_verify (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, verify VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, expire_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3AAAD669A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, report_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C42F77844BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_to_user_order ADD CONSTRAINT FK_EA35964F6D128938 FOREIGN KEY (user_order_id) REFERENCES user_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_to_user_order ADD CONSTRAINT FK_EA35964F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_to_carts ADD CONSTRAINT FK_503D6B2F1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_to_carts ADD CONSTRAINT FK_503D6B2F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_order ADD CONSTRAINT FK_17EB68C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_order ADD CONSTRAINT FK_17EB68C013481D2B FOREIGN KEY (farmer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_to_user ADD CONSTRAINT FK_3270D98CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_to_user ADD CONSTRAINT FK_3270D98C13481D2B FOREIGN KEY (farmer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forget_password_verify ADD CONSTRAINT FK_3AAAD669A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77844BD2A4C0 FOREIGN KEY (report_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE products_to_carts DROP FOREIGN KEY FK_503D6B2F1AD5CDBF');
        $this->addSql('ALTER TABLE product_to_user_order DROP FOREIGN KEY FK_EA35964F4584665A');
        $this->addSql('ALTER TABLE products_to_carts DROP FOREIGN KEY FK_503D6B2F4584665A');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77844BD2A4C0');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE user_order DROP FOREIGN KEY FK_17EB68C0A76ED395');
        $this->addSql('ALTER TABLE user_order DROP FOREIGN KEY FK_17EB68C013481D2B');
        $this->addSql('ALTER TABLE user_to_user DROP FOREIGN KEY FK_3270D98CA76ED395');
        $this->addSql('ALTER TABLE user_to_user DROP FOREIGN KEY FK_3270D98C13481D2B');
        $this->addSql('ALTER TABLE forget_password_verify DROP FOREIGN KEY FK_3AAAD669A76ED395');
        $this->addSql('ALTER TABLE product_to_user_order DROP FOREIGN KEY FK_EA35964F6D128938');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user_email_verify_token');
        $this->addSql('DROP TABLE product_to_user_order');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE products_to_carts');
        $this->addSql('DROP TABLE user_order');
        $this->addSql('DROP TABLE user_to_user');
        $this->addSql('DROP TABLE forget_password_verify');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE report');
    }
}
