<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200331122919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE acompte (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CE996BEC7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acompte ADD CONSTRAINT FK_CE996BEC7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE client CHANGE entreprise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE description CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT NULL, CHANGE unite unite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE devis CHANGE montant_ht montant_ht DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles JSON NOT NULL, CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT NULL, CHANGE crd crd DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE acompte');
        $this->addSql('ALTER TABLE client CHANGE entreprise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE description CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT \'NULL\', CHANGE unite unite VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE devis CHANGE montant_ht montant_ht DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE intitule intitule VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT \'NULL\', CHANGE crd crd DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
