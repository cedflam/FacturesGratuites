<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200331121022 extends AbstractMigration
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
        $this->addSql('ALTER TABLE description ADD devis_id INT DEFAULT NULL, CHANGE facture_id facture_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT NULL, CHANGE unite unite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE description ADD CONSTRAINT FK_6DE4402641DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('CREATE INDEX IDX_6DE4402641DEFADA ON description (devis_id)');
        $this->addSql('ALTER TABLE devis ADD date_devis DATE NOT NULL, ADD montant_ht DOUBLE PRECISION DEFAULT NULL, ADD montant_ttc DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles JSON NOT NULL, CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT NULL, CHANGE crd crd DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE entreprise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE description DROP FOREIGN KEY FK_6DE4402641DEFADA');
        $this->addSql('DROP INDEX IDX_6DE4402641DEFADA ON description');
        $this->addSql('ALTER TABLE description DROP devis_id, CHANGE facture_id facture_id INT DEFAULT NULL, CHANGE quantite quantite DOUBLE PRECISION DEFAULT \'NULL\', CHANGE unite unite VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE devis DROP date_devis, DROP montant_ht, DROP montant_ttc');
        $this->addSql('ALTER TABLE entreprise CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE intitule intitule VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE facture CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE total_acompte total_acompte DOUBLE PRECISION DEFAULT \'NULL\', CHANGE crd crd DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
