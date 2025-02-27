<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217210457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_basket_product (order_id INT NOT NULL, basket_product_id INT NOT NULL, PRIMARY KEY(order_id, basket_product_id))');
        $this->addSql('CREATE INDEX IDX_7F9DC4048D9F6D38 ON order_basket_product (order_id)');
        $this->addSql('CREATE INDEX IDX_7F9DC4045403DA7B ON order_basket_product (basket_product_id)');
        $this->addSql('ALTER TABLE order_basket_product ADD CONSTRAINT FK_7F9DC4048D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_basket_product ADD CONSTRAINT FK_7F9DC4045403DA7B FOREIGN KEY (basket_product_id) REFERENCES basket_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT fk_2530ade68d9f6d38');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT fk_2530ade64584665a');
        $this->addSql('DROP TABLE order_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('CREATE INDEX idx_2530ade64584665a ON order_product (product_id)');
        $this->addSql('CREATE INDEX idx_2530ade68d9f6d38 ON order_product (order_id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT fk_2530ade68d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT fk_2530ade64584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_basket_product DROP CONSTRAINT FK_7F9DC4048D9F6D38');
        $this->addSql('ALTER TABLE order_basket_product DROP CONSTRAINT FK_7F9DC4045403DA7B');
        $this->addSql('DROP TABLE order_basket_product');
    }
}
