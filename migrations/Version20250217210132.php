<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217210132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT FK_17ED14B41BE1FB52');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT FK_17ED14B44584665A');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT basket_product_pkey');
        $this->addSql('ALTER TABLE basket_product ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE basket_product ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE basket_product ALTER basket_id DROP NOT NULL');
        $this->addSql('ALTER TABLE basket_product ALTER product_id DROP NOT NULL');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B41BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B44584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT fk_17ed14b41be1fb52');
        $this->addSql('ALTER TABLE basket_product DROP CONSTRAINT fk_17ed14b44584665a');
        $this->addSql('DROP INDEX basket_product_pkey');
        $this->addSql('ALTER TABLE basket_product DROP id');
        $this->addSql('ALTER TABLE basket_product DROP quantity');
        $this->addSql('ALTER TABLE basket_product ALTER basket_id SET NOT NULL');
        $this->addSql('ALTER TABLE basket_product ALTER product_id SET NOT NULL');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT fk_17ed14b41be1fb52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT fk_17ed14b44584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE basket_product ADD PRIMARY KEY (basket_id, product_id)');
    }
}
