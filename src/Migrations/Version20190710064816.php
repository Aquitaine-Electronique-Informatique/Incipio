<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710064816 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Pole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(127) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mood_answer (id INT AUTO_INCREMENT NOT NULL, mood_question_id INT DEFAULT NULL, member_id INT DEFAULT NULL, answer1 INT NOT NULL, answer2 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', answer3 LONGTEXT DEFAULT NULL, answeredAt DATETIME DEFAULT NULL, INDEX IDX_B9D2A7C2E54A0BCC (mood_question_id), INDEX IDX_B9D2A7C27597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mood_question (id INT AUTO_INCREMENT NOT NULL, askedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moodstat (id INT AUTO_INCREMENT NOT NULL, mood_question_id INT DEFAULT NULL, pole_id INT DEFAULT NULL, promotion SMALLINT DEFAULT NULL, month SMALLINT NOT NULL, no_answer SMALLINT NOT NULL, answer SMALLINT NOT NULL, medium_mood SMALLINT NOT NULL, INDEX IDX_C7DE1A30E54A0BCC (mood_question_id), INDEX IDX_C7DE1A30419C3385 (pole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE polestat (id INT AUTO_INCREMENT NOT NULL, reactivity_question_id INT DEFAULT NULL, pole_id INT DEFAULT NULL, promotion SMALLINT DEFAULT NULL, month SMALLINT NOT NULL, no_answer SMALLINT NOT NULL, good_answer SMALLINT NOT NULL, bad_answer SMALLINT NOT NULL, INDEX IDX_9B9465D49C296B17 (reactivity_question_id), INDEX IDX_9B9465D4419C3385 (pole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reactivity_answer (id INT AUTO_INCREMENT NOT NULL, reactivity_question_id INT DEFAULT NULL, member_id INT DEFAULT NULL, answer LONGTEXT DEFAULT NULL, answeredAt DATETIME DEFAULT NULL, isValidated TINYINT(1) NOT NULL, INDEX IDX_6507565A9C296B17 (reactivity_question_id), INDEX IDX_6507565A7597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reactivity_question (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, askedAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mood_answer ADD CONSTRAINT FK_B9D2A7C2E54A0BCC FOREIGN KEY (mood_question_id) REFERENCES mood_question (id)');
        $this->addSql('ALTER TABLE mood_answer ADD CONSTRAINT FK_B9D2A7C27597D3FE FOREIGN KEY (member_id) REFERENCES Membre (id)');
        $this->addSql('ALTER TABLE moodstat ADD CONSTRAINT FK_C7DE1A30E54A0BCC FOREIGN KEY (mood_question_id) REFERENCES mood_question (id)');
        $this->addSql('ALTER TABLE moodstat ADD CONSTRAINT FK_C7DE1A30419C3385 FOREIGN KEY (pole_id) REFERENCES Pole (id)');
        $this->addSql('ALTER TABLE polestat ADD CONSTRAINT FK_9B9465D49C296B17 FOREIGN KEY (reactivity_question_id) REFERENCES reactivity_question (id)');
        $this->addSql('ALTER TABLE polestat ADD CONSTRAINT FK_9B9465D4419C3385 FOREIGN KEY (pole_id) REFERENCES Pole (id)');
        $this->addSql('ALTER TABLE reactivity_answer ADD CONSTRAINT FK_6507565A9C296B17 FOREIGN KEY (reactivity_question_id) REFERENCES reactivity_question (id)');
        $this->addSql('ALTER TABLE reactivity_answer ADD CONSTRAINT FK_6507565A7597D3FE FOREIGN KEY (member_id) REFERENCES Membre (id)');
        $this->addSql('ALTER TABLE Poste ADD pole_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Poste ADD CONSTRAINT FK_BD4820AF419C3385 FOREIGN KEY (pole_id) REFERENCES Pole (id)');
        $this->addSql('CREATE INDEX IDX_BD4820AF419C3385 ON Poste (pole_id)');
        $this->addSql('ALTER TABLE Prospect ADD chaud TINYINT(1) DEFAULT \'1\' NOT NULL, ADD direct TINYINT(1) DEFAULT \'1\' NOT NULL, ADD date_rencontre DATE DEFAULT NULL, ADD interet INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Poste DROP FOREIGN KEY FK_BD4820AF419C3385');
        $this->addSql('ALTER TABLE moodstat DROP FOREIGN KEY FK_C7DE1A30419C3385');
        $this->addSql('ALTER TABLE polestat DROP FOREIGN KEY FK_9B9465D4419C3385');
        $this->addSql('ALTER TABLE mood_answer DROP FOREIGN KEY FK_B9D2A7C2E54A0BCC');
        $this->addSql('ALTER TABLE moodstat DROP FOREIGN KEY FK_C7DE1A30E54A0BCC');
        $this->addSql('ALTER TABLE polestat DROP FOREIGN KEY FK_9B9465D49C296B17');
        $this->addSql('ALTER TABLE reactivity_answer DROP FOREIGN KEY FK_6507565A9C296B17');
        $this->addSql('DROP TABLE Pole');
        $this->addSql('DROP TABLE mood_answer');
        $this->addSql('DROP TABLE mood_question');
        $this->addSql('DROP TABLE moodstat');
        $this->addSql('DROP TABLE polestat');
        $this->addSql('DROP TABLE reactivity_answer');
        $this->addSql('DROP TABLE reactivity_question');
        $this->addSql('DROP INDEX IDX_BD4820AF419C3385 ON Poste');
        $this->addSql('ALTER TABLE Poste DROP pole_id');
        $this->addSql('ALTER TABLE Prospect DROP chaud, DROP direct, DROP date_rencontre, DROP interet');
    }
}
