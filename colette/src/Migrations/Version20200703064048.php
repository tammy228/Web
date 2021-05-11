<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703064048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news CHANGE zh_content zh_content LONGTEXT DEFAULT NULL, CHANGE en_content en_content LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE total_price total_price INT DEFAULT NULL, CHANGE coupon_message coupon_message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product_to_user_order CHANGE product_id product_id INT DEFAULT NULL, CHANGE user_order_id user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE mobile mobile VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product_to_category CHANGE product_id product_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_to_cart CHANGE cart_id cart_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_order CHANGE user_id user_id INT DEFAULT NULL, CHANGE coupon_message coupon_message VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE total_price total_price INT DEFAULT NULL, CHANGE coupon_message coupon_message VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE en_title en_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product_to_cart CHANGE cart_id cart_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_to_category CHANGE product_id product_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_to_user_order CHANGE product_id product_id INT DEFAULT NULL, CHANGE user_order_id user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE mobile mobile VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_order CHANGE user_id user_id INT DEFAULT NULL, CHANGE coupon_message coupon_message VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
