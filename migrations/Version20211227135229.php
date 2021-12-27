<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227135229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refurbish_state (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refurbished_toy (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, state_id INT NOT NULL, bar_code_number VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4533C8649395C3F3 (customer_id), INDEX IDX_4533C8645D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refurbished_toy ADD CONSTRAINT FK_4533C8649395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE refurbished_toy ADD CONSTRAINT FK_4533C8645D83CC1 FOREIGN KEY (state_id) REFERENCES refurbish_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE refurbished_toy DROP FOREIGN KEY FK_4533C8645D83CC1');
        $this->addSql('DROP TABLE refurbish_state');
        $this->addSql('DROP TABLE refurbished_toy');
    }
}
