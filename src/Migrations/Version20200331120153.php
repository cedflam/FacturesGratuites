<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200331120153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, INDEX IDX_C7440455A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE description (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, prestation VARCHAR(1000) NOT NULL, date_prestation DATE NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, unite VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_6DE440267F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, intitule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D19FA60E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, client_id INT DEFAULT NULL, date_facture DATE NOT NULL, message VARCHAR(255) NOT NULL, total_acompte DOUBLE PRECISION DEFAULT NULL, crd DOUBLE PRECISION DEFAULT NULL, montant_ht DOUBLE PRECISION NOT NULL, montant_ttc DOUBLE PRECISION NOT NULL, INDEX IDX_FE866410A4AEAFEA (entreprise_id), INDEX IDX_FE86641019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE description ADD CONSTRAINT FK_6DE440267F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A4AEAFEA');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A4AEAFEA');
        $this->addSql('ALTER TABLE description DROP FOREIGN KEY FK_6DE440267F2DEE08');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE description');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE facture');
    }
}
