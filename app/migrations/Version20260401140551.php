<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401140551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_351268BBA76ED395 ON abonnement (user_id)');
        $this->addSql('ALTER TABLE workshop ADD coach_id INT NOT NULL');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C43C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('CREATE INDEX IDX_9B6F02C43C105691 ON workshop (coach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('DROP INDEX UNIQ_351268BBA76ED395 ON abonnement');
        $this->addSql('ALTER TABLE abonnement DROP user_id');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C43C105691');
        $this->addSql('DROP INDEX IDX_9B6F02C43C105691 ON workshop');
        $this->addSql('ALTER TABLE workshop DROP coach_id');
    }
}
