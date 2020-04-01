<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200331121338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE entreprise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE description DROP FOREIGN KEY FK_6DE440267F2DEE08');
        $this->addSql('DROP INDEX IDX_6DE440267F2DEE08 ON description');
        $this->addSql('ALTER TABLE description DROP facture_id, CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT NULL, CHANGE unite unite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE devis CHANGE montant_ht montant_ht DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles JSON NOT NULL, CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT NULL, CHANGE crd crd DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE entreprise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE description ADD facture_id INT DEFAULT NULL, CHANGE devis_id devis_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT \'NULL\', CHANGE unite unite VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE description ADD CONSTRAINT FK_6DE440267F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE INDEX IDX_6DE440267F2DEE08 ON description (facture_id)');
        $this->addSql('ALTER TABLE devis CHANGE montant_ht montant_ht DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE intitule intitule VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT \'NULL\', CHANGE crd crd DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
