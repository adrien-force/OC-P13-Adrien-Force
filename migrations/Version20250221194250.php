<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221194250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE basket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE basket_product_id_seq CASCADE');
        $this->addSql('CREATE TABLE order_item (id SERIAL NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket DROP CONSTRAINT fk_2246507b7e3c61f9');
        $this->addSql('ALTER TABLE order_basket_product DROP CONSTRAINT fk_7f9dc4048d9f6d38');
        $this->addSql('ALTER TABLE order_basket_product DROP CONSTRAINT fk_7f9dc4045403da7b');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT fk_17ed14b41be1fb52');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT fk_17ed14b44584665a');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT fk_17ed14b48d9f6d38');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE order_basket_product');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('DROP INDEX idx_f52993987e3c61f9');
        $this->addSql('ALTER TABLE "order" DROP ordered_at');
        $this->addSql('ALTER TABLE "order" ALTER owner_id DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993987E3C61F9 ON "order" (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE basket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE basket_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE basket (id SERIAL NOT NULL, owner_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_2246507b7e3c61f9 ON basket (owner_id)');
        $this->addSql('CREATE TABLE order_basket_product (order_id INT NOT NULL, basket_product_id INT NOT NULL, PRIMARY KEY(order_id, basket_product_id))');
        $this->addSql('CREATE INDEX idx_7f9dc4045403da7b ON order_basket_product (basket_product_id)');
        $this->addSql('CREATE INDEX idx_7f9dc4048d9f6d38 ON order_basket_product (order_id)');
        $this->addSql('CREATE TABLE basket_product (id SERIAL NOT NULL, basket_id INT DEFAULT NULL, product_id INT DEFAULT NULL, order_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_17ed14b48d9f6d38 ON basket_product (order_id)');
        $this->addSql('CREATE INDEX idx_17ed14b44584665a ON basket_product (product_id)');
        $this->addSql('CREATE INDEX idx_17ed14b41be1fb52 ON basket_product (basket_id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT fk_2246507b7e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_basket_product ADD CONSTRAINT fk_7f9dc4048d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_basket_product ADD CONSTRAINT fk_7f9dc4045403da7b FOREIGN KEY (basket_product_id) REFERENCES basket_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT fk_17ed14b41be1fb52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT fk_17ed14b44584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT fk_17ed14b48d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP INDEX UNIQ_F52993987E3C61F9');
        $this->addSql('ALTER TABLE "order" ADD ordered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER owner_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN "order".ordered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX idx_f52993987e3c61f9 ON "order" (owner_id)');
    }
}
