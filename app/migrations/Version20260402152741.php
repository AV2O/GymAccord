<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402152741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscribes_workshops_type (subscribes_id INT NOT NULL, workshops_type_id INT NOT NULL, INDEX IDX_81206A71C0B4D6C8 (subscribes_id), INDEX IDX_81206A715650D68C (workshops_type_id), PRIMARY KEY (subscribes_id, workshops_type_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE subscribes_workshops_type ADD CONSTRAINT FK_81206A71C0B4D6C8 FOREIGN KEY (subscribes_id) REFERENCES subscribes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscribes_workshops_type ADD CONSTRAINT FK_81206A715650D68C FOREIGN KEY (workshops_type_id) REFERENCES workshops_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservations ADD user_id INT NOT NULL, ADD workshop_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2391FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshops (id)');
        $this->addSql('CREATE INDEX IDX_4DA239A76ED395 ON reservations (user_id)');
        $this->addSql('CREATE INDEX IDX_4DA2391FDCE57C ON reservations (workshop_id)');
        $this->addSql('ALTER TABLE take ADD user_id INT NOT NULL, ADD subscribe_id INT NOT NULL');
        $this->addSql('ALTER TABLE take ADD CONSTRAINT FK_37DD6E7BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE take ADD CONSTRAINT FK_37DD6E7BC72A4771 FOREIGN KEY (subscribe_id) REFERENCES subscribes (id)');
        $this->addSql('CREATE INDEX IDX_37DD6E7BA76ED395 ON take (user_id)');
        $this->addSql('CREATE INDEX IDX_37DD6E7BC72A4771 ON take (subscribe_id)');
        $this->addSql('ALTER TABLE workshops ADD coach_id INT NOT NULL, ADD workshop_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE workshops ADD CONSTRAINT FK_879CA6A03C105691 FOREIGN KEY (coach_id) REFERENCES coachs (id)');
        $this->addSql('ALTER TABLE workshops ADD CONSTRAINT FK_879CA6A087039E84 FOREIGN KEY (workshop_type_id) REFERENCES workshops_type (id)');
        $this->addSql('CREATE INDEX IDX_879CA6A03C105691 ON workshops (coach_id)');
        $this->addSql('CREATE INDEX IDX_879CA6A087039E84 ON workshops (workshop_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscribes_workshops_type DROP FOREIGN KEY FK_81206A71C0B4D6C8');
        $this->addSql('ALTER TABLE subscribes_workshops_type DROP FOREIGN KEY FK_81206A715650D68C');
        $this->addSql('DROP TABLE subscribes_workshops_type');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2391FDCE57C');
        $this->addSql('DROP INDEX IDX_4DA239A76ED395 ON reservations');
        $this->addSql('DROP INDEX IDX_4DA2391FDCE57C ON reservations');
        $this->addSql('ALTER TABLE reservations DROP user_id, DROP workshop_id');
        $this->addSql('ALTER TABLE take DROP FOREIGN KEY FK_37DD6E7BA76ED395');
        $this->addSql('ALTER TABLE take DROP FOREIGN KEY FK_37DD6E7BC72A4771');
        $this->addSql('DROP INDEX IDX_37DD6E7BA76ED395 ON take');
        $this->addSql('DROP INDEX IDX_37DD6E7BC72A4771 ON take');
        $this->addSql('ALTER TABLE take DROP user_id, DROP subscribe_id');
        $this->addSql('ALTER TABLE workshops DROP FOREIGN KEY FK_879CA6A03C105691');
        $this->addSql('ALTER TABLE workshops DROP FOREIGN KEY FK_879CA6A087039E84');
        $this->addSql('DROP INDEX IDX_879CA6A03C105691 ON workshops');
        $this->addSql('DROP INDEX IDX_879CA6A087039E84 ON workshops');
        $this->addSql('ALTER TABLE workshops DROP coach_id, DROP workshop_type_id');
    }
}
