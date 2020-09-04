<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200904142520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concurrent (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, ville VARCHAR(150) NOT NULL, tel VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(10) NOT NULL, libelle VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_vendeur (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, etat_id INT NOT NULL, plancher NUMERIC(10, 2) NOT NULL, prix NUMERIC(10, 2) NOT NULL, INDEX IDX_9726DD217294869C (article_id), INDEX IDX_9726DD21D5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_concurrent (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, concurrent_id INT NOT NULL, etat_id INT NOT NULL, prix NUMERIC(10, 2) NOT NULL, INDEX IDX_E2A7110D7294869C (article_id), INDEX IDX_E2A7110DF229AC7F (concurrent_id), INDEX IDX_E2A7110DD5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_vendeur ADD CONSTRAINT FK_9726DD217294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_vendeur ADD CONSTRAINT FK_9726DD21D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE article_concurrent ADD CONSTRAINT FK_E2A7110D7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_concurrent ADD CONSTRAINT FK_E2A7110DF229AC7F FOREIGN KEY (concurrent_id) REFERENCES concurrent (id)');
        $this->addSql('ALTER TABLE article_concurrent ADD CONSTRAINT FK_E2A7110DD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_concurrent DROP FOREIGN KEY FK_E2A7110D7294869C');
        $this->addSql('ALTER TABLE article_vendeur DROP FOREIGN KEY FK_9726DD217294869C');
        $this->addSql('ALTER TABLE article_concurrent DROP FOREIGN KEY FK_E2A7110DF229AC7F');
        $this->addSql('ALTER TABLE article_concurrent DROP FOREIGN KEY FK_E2A7110DD5E86FF');
        $this->addSql('ALTER TABLE article_vendeur DROP FOREIGN KEY FK_9726DD21D5E86FF');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_concurrent');
        $this->addSql('DROP TABLE article_vendeur');
        $this->addSql('DROP TABLE concurrent');
        $this->addSql('DROP TABLE etat');
    }
}
