<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107153947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parser_games (id UUID NOT NULL, id_ps VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, price_discount INT NOT NULL, version VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, discount_end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, update_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN parser_games.id IS \'(DC2Type:game_id)\'');
        $this->addSql('COMMENT ON COLUMN parser_games.price IS \'(DC2Type:game_price)\'');
        $this->addSql('COMMENT ON COLUMN parser_games.price_discount IS \'(DC2Type:game_price)\'');
        $this->addSql('COMMENT ON COLUMN parser_games.discount_end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN parser_games.create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN parser_games.update_date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE parser_games');
    }
}
