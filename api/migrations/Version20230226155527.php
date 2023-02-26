<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226155527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create school table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE school (
                numero_uai VARCHAR(8) NOT NULL,
                appellation_officielle VARCHAR(250) DEFAULT NULL,
                denomination_principale VARCHAR(100) DEFAULT NULL,
                patronyme_uai VARCHAR(100) DEFAULT NULL, 
                secteur_public_prive_libe VARCHAR(10) NOT NULL,
                adresse_uai VARCHAR(100) DEFAULT NULL,
                lieu_dit_uai VARCHAR(100) DEFAULT NULL,
                boite_postale_uai VARCHAR(10) DEFAULT NULL,
                code_postal_uai VARCHAR(5) NOT NULL,
                localite_acheminement_uai VARCHAR(50) NOT NULL,
                libelle_commune VARCHAR(100) NOT NULL,
                coordonnee_x DOUBLE PRECISION DEFAULT NULL,
                coordonnee_y DOUBLE PRECISION DEFAULT NULL,
                epsg VARCHAR(50) DEFAULT NULL,
                latitude DOUBLE PRECISION DEFAULT NULL,
                longitude DOUBLE PRECISION DEFAULT NULL,
                appariement VARCHAR(25) DEFAULT NULL,
                localisation VARCHAR(50) DEFAULT NULL,
                nature_uai INT NOT NULL,
                nature_uai_libe VARCHAR(100) NOT NULL,
                etat_etablissement INT NOT NULL,
                etat_etablissement_libe VARCHAR(20) NOT NULL,
                code_departement VARCHAR(3) NOT NULL,
                code_region VARCHAR(2) NOT NULL,
                code_academie VARCHAR(2) NOT NULL,
                code_commune VARCHAR(5) NOT NULL,
                libelle_departement VARCHAR(50) NOT NULL,
                libelle_region VARCHAR(50) NOT NULL,
                libelle_academie VARCHAR(50) NOT NULL,
                position POINT DEFAULT NULL COMMENT \'(DC2Type:point)\',
                secteur_prive_code_type_contrat INT DEFAULT NULL,
                secteur_prive_libelle_type_contrat VARCHAR(100) DEFAULT NULL,
                code_ministere VARCHAR(2) NOT NULL,
                libelle_ministere VARCHAR(50) NOT NULL,
                date_ouverture DATE NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                
                INDEX idx_zipcode (code_postal_uai),
                INDEX idx_city (libelle_commune),
                INDEX idx_name (denomination_principale),
                INDEX idx_state (etat_etablissement),
                INDEX idx_kind (nature_uai),
                INDEX idx_private (secteur_public_prive_libe),
                
                PRIMARY KEY(numero_uai)
            )
            
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE school;');
    }
}
