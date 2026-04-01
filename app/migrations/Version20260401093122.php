<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401093122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(100) NOT NULL, ADD first_name VARCHAR(100) NOT NULL, ADD birthday DATE DEFAULT NULL, ADD telephone VARCHAR(14) DEFAULT NULL, ADD adress VARCHAR(255) NOT NULL, ADD city VARCHAR(100) NOT NULL, ADD zip_code VARCHAR(6) NOT NULL, ADD created_at DATETIME NOT NULL, ADD actif TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP last_name, DROP first_name, DROP birthday, DROP telephone, DROP adress, DROP city, DROP zip_code, DROP created_at, DROP actif');
    }
}
