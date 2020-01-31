<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200131225002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE locale (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kalender (id INT AUTO_INCREMENT NOT NULL, kalender VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kalender_tekst (id INT AUTO_INCREMENT NOT NULL, locale_id INT NOT NULL, kalender_id INT NOT NULL, bid_id INT NOT NULL, months LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ugedage LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_A371E50E559DFD1 (locale_id), INDEX IDX_A371E504503E54D (kalender_id), INDEX IDX_A371E504D9866B8 (bid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, bid INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kalender_tekst ADD CONSTRAINT FK_A371E50E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE kalender_tekst ADD CONSTRAINT FK_A371E504503E54D FOREIGN KEY (kalender_id) REFERENCES kalender (id)');
        $this->addSql('ALTER TABLE kalender_tekst ADD CONSTRAINT FK_A371E504D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE kalender_tekst DROP FOREIGN KEY FK_A371E50E559DFD1');
        $this->addSql('ALTER TABLE kalender_tekst DROP FOREIGN KEY FK_A371E504503E54D');
        $this->addSql('ALTER TABLE kalender_tekst DROP FOREIGN KEY FK_A371E504D9866B8');
        $this->addSql('DROP TABLE locale');
        $this->addSql('DROP TABLE kalender');
        $this->addSql('DROP TABLE kalender_tekst');
        $this->addSql('DROP TABLE bid');
    }
}
