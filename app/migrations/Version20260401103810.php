<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401103810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, statut VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, speciality VARCHAR(100) NOT NULL, bio LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workshop (id INT AUTO_INCREMENT NOT NULL, name_class VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date_class DATE NOT NULL, collectif_class TINYINT NOT NULL, length_class INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE telephone telephone VARCHAR(14) DEFAULT NULL, CHANGE adress adress VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(100) NOT NULL, CHANGE zip_code zip_code VARCHAR(6) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE workshop');
        $this->addSql('ALTER TABLE user DROP roles, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE last_name last_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE first_name first_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE telephone telephone VARCHAR(14) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE adress adress VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE city city VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE zip_code zip_code VARCHAR(6) NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
