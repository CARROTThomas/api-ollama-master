<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221092601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mot_du_jour_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mot_du_jour (id INT NOT NULL, mot_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9464ECDA63977652 ON mot_du_jour (mot_id)');
        $this->addSql('COMMENT ON COLUMN mot_du_jour.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE mot_du_jour ADD CONSTRAINT FK_9464ECDA63977652 FOREIGN KEY (mot_id) REFERENCES mot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mot_du_jour_id_seq CASCADE');
        $this->addSql('ALTER TABLE mot_du_jour DROP CONSTRAINT FK_9464ECDA63977652');
        $this->addSql('DROP TABLE mot_du_jour');
    }
}
