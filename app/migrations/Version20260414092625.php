<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260414092625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY `FK_4DA2391FDCE57C`');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY `FK_4DA239A76ED395`');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('ALTER TABLE users ADD profile_picture VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, workshop_date DATETIME NOT NULL, user_id INT NOT NULL, workshop_id INT NOT NULL, INDEX IDX_4DA2391FDCE57C (workshop_id), INDEX IDX_4DA239A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT `FK_4DA2391FDCE57C` FOREIGN KEY (workshop_id) REFERENCES workshops (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT `FK_4DA239A76ED395` FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users DROP profile_picture');
    }
}
