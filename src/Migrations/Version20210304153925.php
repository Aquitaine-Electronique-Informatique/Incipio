<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304153925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Etude ADD suiveurProspection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Etude ADD CONSTRAINT FK_DC1F862018D1D2C0 FOREIGN KEY (suiveurProspection_id) REFERENCES Personne (id)');
        $this->addSql('CREATE INDEX IDX_DC1F862018D1D2C0 ON Etude (suiveurProspection_id)');
        $this->addSql('ALTER TABLE RelatedDocument ADD thread_id VARCHAR(255) DEFAULT NULL, ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE RelatedDocument ADD CONSTRAINT FK_E28BFD66E2904019 FOREIGN KEY (thread_id) REFERENCES Thread (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E28BFD66E2904019 ON RelatedDocument (thread_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Etude DROP FOREIGN KEY FK_DC1F862018D1D2C0');
        $this->addSql('DROP INDEX IDX_DC1F862018D1D2C0 ON Etude');
        $this->addSql('ALTER TABLE Etude DROP suiveurProspection_id');
        $this->addSql('ALTER TABLE RelatedDocument DROP FOREIGN KEY FK_E28BFD66E2904019');
        $this->addSql('DROP INDEX UNIQ_E28BFD66E2904019 ON RelatedDocument');
        $this->addSql('ALTER TABLE RelatedDocument DROP thread_id, DROP status');
    }
}
