<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228094003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conversation_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conversation_entry (id INT NOT NULL, profile_id INT NOT NULL, question TEXT NOT NULL, response TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FF7BE5CCCFA12B8 ON conversation_entry (profile_id)');
        $this->addSql('ALTER TABLE conversation_entry ADD CONSTRAINT FK_3FF7BE5CCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE conversation_entry_id_seq CASCADE');
        $this->addSql('ALTER TABLE conversation_entry DROP CONSTRAINT FK_3FF7BE5CCCFA12B8');
        $this->addSql('DROP TABLE conversation_entry');
    }
}
