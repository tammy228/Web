<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200604124346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, en_title VARCHAR(255) DEFAULT NULL, zh_title VARCHAR(255) NOT NULL, en_content TINYTEXT DEFAULT NULL, zh_content TINYTEXT NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', share INT NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1DD39950D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, zh_name VARCHAR(255) NOT NULL, en_name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C1D17F50A6 (uuid), INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, total_price INT DEFAULT NULL, coupon_message VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_BA388B7D17F50A6 (uuid), UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, zh_name VARCHAR(255) NOT NULL, en_name VARCHAR(255) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', zh_description LONGTEXT NOT NULL, en_description LONGTEXT NOT NULL, price LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stock LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', size LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', temperature VARCHAR(255) NOT NULL, deleted TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banner (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6F9DB8E7D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_to_user_order (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_order_id INT DEFAULT NULL, quantity INT NOT NULL, size VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_EA35964F4584665A (product_id), INDEX IDX_EA35964F6D128938 (user_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, email_validated TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, role_codes INT NOT NULL, password VARCHAR(255) NOT NULL, mobile VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, deleted INT NOT NULL, create_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, owner VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, keyword VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, shipping_standard VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_to_category (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_673A19704584665A (product_id), INDEX IDX_673A197012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_verify (id INT AUTO_INCREMENT NOT NULL, user_id LONGTEXT NOT NULL, token VARCHAR(255) NOT NULL, verify_code INT NOT NULL, create_at DATETIME NOT NULL, expire_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_us (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, uuid VARCHAR(255) NOT NULL, complete TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8205FDD0D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_to_cart (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, size VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_3D9482C81AD5CDBF (cart_id), INDEX IDX_3D9482C84584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, status INT NOT NULL, delivery VARCHAR(255) NOT NULL, remark VARCHAR(255) NOT NULL, payment VARCHAR(255) NOT NULL, merchant_trade_no VARCHAR(255) NOT NULL, recipient_name VARCHAR(255) NOT NULL, recipient_mobile VARCHAR(255) NOT NULL, recipient_email VARCHAR(255) NOT NULL, recipient_address VARCHAR(255) NOT NULL, total_price INT NOT NULL, coupon_message VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_17EB68C0D17F50A6 (uuid), INDEX IDX_17EB68C0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, type INT NOT NULL, target INT NOT NULL, code VARCHAR(255) NOT NULL, number INT NOT NULL, expire_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64BF3F02D17F50A6 (uuid), UNIQUE INDEX UNIQ_64BF3F0277153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_to_user_order ADD CONSTRAINT FK_EA35964F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_to_user_order ADD CONSTRAINT FK_EA35964F6D128938 FOREIGN KEY (user_order_id) REFERENCES user_order (id)');
        $this->addSql('ALTER TABLE product_to_category ADD CONSTRAINT FK_673A19704584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_to_category ADD CONSTRAINT FK_673A197012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_to_cart ADD CONSTRAINT FK_3D9482C81AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_to_cart ADD CONSTRAINT FK_3D9482C84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_order ADD CONSTRAINT FK_17EB68C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE product_to_category DROP FOREIGN KEY FK_673A197012469DE2');
        $this->addSql('ALTER TABLE product_to_cart DROP FOREIGN KEY FK_3D9482C81AD5CDBF');
        $this->addSql('ALTER TABLE product_to_user_order DROP FOREIGN KEY FK_EA35964F4584665A');
        $this->addSql('ALTER TABLE product_to_category DROP FOREIGN KEY FK_673A19704584665A');
        $this->addSql('ALTER TABLE product_to_cart DROP FOREIGN KEY FK_3D9482C84584665A');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE user_order DROP FOREIGN KEY FK_17EB68C0A76ED395');
        $this->addSql('ALTER TABLE product_to_user_order DROP FOREIGN KEY FK_EA35964F6D128938');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE banner');
        $this->addSql('DROP TABLE product_to_user_order');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE product_to_category');
        $this->addSql('DROP TABLE user_verify');
        $this->addSql('DROP TABLE contact_us');
        $this->addSql('DROP TABLE product_to_cart');
        $this->addSql('DROP TABLE user_order');
        $this->addSql('DROP TABLE coupon');
    }
}
