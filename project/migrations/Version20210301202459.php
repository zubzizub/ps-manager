<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301202459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE store_games ADD price_price INT NOT NULL');
        $this->addSql('ALTER TABLE store_games ADD price_lower_price INT NOT NULL');
        $this->addSql('ALTER TABLE store_games ADD price_discount_end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE store_games DROP price');
        $this->addSql('ALTER TABLE store_games DROP price_discount');
        $this->addSql('ALTER TABLE store_games DROP discount_end_date');
        $this->addSql('COMMENT ON COLUMN store_games.price_discount_end_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE store_games ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE store_games ADD price_discount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_games ADD discount_end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE store_games DROP price_price');
        $this->addSql('ALTER TABLE store_games DROP price_lower_price');
        $this->addSql('ALTER TABLE store_games DROP price_discount_end_date');
        $this->addSql('COMMENT ON COLUMN store_games.price IS \'(DC2Type:game_price)\'');
        $this->addSql('COMMENT ON COLUMN store_games.price_discount IS \'(DC2Type:game_price)\'');
        $this->addSql('COMMENT ON COLUMN store_games.discount_end_date IS \'(DC2Type:datetime_immutable)\'');
    }
}
