<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222213142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_user_networks (id UUID NOT NULL, user_id UUID NOT NULL, network VARCHAR(32) DEFAULT NULL, identity VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EA78C3BA76ED395 ON auth_user_networks (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EA78C3B608487BC6A95E9C4 ON auth_user_networks (network, identity)');
        $this->addSql('CREATE TABLE auth_users (id UUID NOT NULL, email VARCHAR(255) DEFAULT NULL, status VARCHAR(16) NOT NULL, password_hash VARCHAR(500) NOT NULL, confirm_token VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, role VARCHAR(255) NOT NULL, reset_token_token VARCHAR(255) DEFAULT NULL, reset_token_expires DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8A1F49CE7927C74 ON auth_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8A1F49C86EC69F0 ON auth_users (reset_token_token)');
        $this->addSql('COMMENT ON COLUMN auth_users.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.reset_token_expires IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE auth_user_networks ADD CONSTRAINT FK_3EA78C3BA76ED395 FOREIGN KEY (user_id) REFERENCES auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auth_user_networks DROP CONSTRAINT FK_3EA78C3BA76ED395');
        $this->addSql('DROP TABLE auth_user_networks');
        $this->addSql('DROP TABLE auth_users');
    }
}
