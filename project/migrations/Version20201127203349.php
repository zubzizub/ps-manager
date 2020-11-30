<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127203349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parser_games ALTER price_discount DROP NOT NULL');
        $this->addSql('ALTER TABLE parser_games ALTER create_date SET NOT NULL');
        $this->addSql('ALTER TABLE parser_games RENAME COLUMN id_ps TO external_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE parser_games ALTER price_discount SET NOT NULL');
        $this->addSql('ALTER TABLE parser_games ALTER create_date DROP NOT NULL');
        $this->addSql('ALTER TABLE parser_games RENAME COLUMN external_id TO id_ps');
    }
}
