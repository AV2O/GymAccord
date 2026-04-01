<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401132810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement CHANGE statut statut VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE coach CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE speciality speciality VARCHAR(100) NOT NULL, CHANGE bio bio LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD user_id INT NOT NULL, ADD workshop_id INT NOT NULL, CHANGE statut statut VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE INDEX IDX_42C849551FDCE57C ON reservation (workshop_id)');
        $this->addSql('ALTER TABLE workshop DROP statut, CHANGE name_class name_class VARCHAR(100) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement CHANGE statut statut ENUM(\'Early\', \'Premium\', \'Gold\', \'\') NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE coach CHANGE last_name last_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE first_name first_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE speciality speciality VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE bio bio LONGTEXT NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551FDCE57C');
        $this->addSql('DROP INDEX IDX_42C84955A76ED395 ON reservation');
        $this->addSql('DROP INDEX IDX_42C849551FDCE57C ON reservation');
        $this->addSql('ALTER TABLE reservation DROP user_id, DROP workshop_id, CHANGE statut statut ENUM(\'Confirmé\', \'Annulé\', \'En attente\', \'\') NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE workshop ADD statut ENUM(\'Complet\', \'Disponible\') NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE name_class name_class VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
