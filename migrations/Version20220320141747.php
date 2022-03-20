<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220320141747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, fidelity_points INT NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift_code (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_on DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', number_uses_limit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift_code_to_customer (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, gift_code_id INT NOT NULL, number_used INT NOT NULL, INDEX IDX_CDED996C9395C3F3 (customer_id), INDEX IDX_CDED996C4B80CDB8 (gift_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, label VARCHAR(255) NOT NULL, file_name VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, state_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', tracking_number VARCHAR(255) DEFAULT NULL, estimated_delivery DATE DEFAULT NULL, INDEX IDX_F52993989395C3F3 (customer_id), INDEX IDX_F52993985D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price NUMERIC(8, 2) NOT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), INDEX IDX_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(8, 2) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refurbish_state (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refurbished_toy (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, state_id INT NOT NULL, bar_code_number VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, INDEX IDX_4533C8649395C3F3 (customer_id), INDEX IDX_4533C8645D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_back (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_174F8DFEE7927C74 (email), INDEX IDX_174F8DFEB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_on DATE NOT NULL, amount NUMERIC(8, 2) NOT NULL, INDEX IDX_1392A5D89395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gift_code_to_customer ADD CONSTRAINT FK_CDED996C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE gift_code_to_customer ADD CONSTRAINT FK_CDED996C4B80CDB8 FOREIGN KEY (gift_code_id) REFERENCES gift_code (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE refurbished_toy ADD CONSTRAINT FK_4533C8649395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE refurbished_toy ADD CONSTRAINT FK_4533C8645D83CC1 FOREIGN KEY (state_id) REFERENCES refurbish_state (id)');
        $this->addSql('ALTER TABLE user_back ADD CONSTRAINT FK_174F8DFEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user_back (id)');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D89395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gift_code_to_customer DROP FOREIGN KEY FK_CDED996C9395C3F3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE refurbished_toy DROP FOREIGN KEY FK_4533C8649395C3F3');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D89395C3F3');
        $this->addSql('ALTER TABLE gift_code_to_customer DROP FOREIGN KEY FK_CDED996C4B80CDB8');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE refurbished_toy DROP FOREIGN KEY FK_4533C8645D83CC1');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985D83CC1');
        $this->addSql('ALTER TABLE user_back DROP FOREIGN KEY FK_174F8DFEB03A8386');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE gift_code');
        $this->addSql('DROP TABLE gift_code_to_customer');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE refurbish_state');
        $this->addSql('DROP TABLE refurbished_toy');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE user_back');
        $this->addSql('DROP TABLE voucher');
    }
}
