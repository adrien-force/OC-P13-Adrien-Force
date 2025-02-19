<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219191937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_product ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B48D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_17ED14B48D9F6D38 ON basket_product (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT FK_17ED14B48D9F6D38');
        $this->addSql('DROP INDEX IDX_17ED14B48D9F6D38');
        $this->addSql('ALTER TABLE basket_product DROP order_id');
    }
}
