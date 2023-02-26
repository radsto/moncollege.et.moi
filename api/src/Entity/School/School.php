<?php

namespace App\Entity\School;

use App\Repository\School\SchoolRepository;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['code_postal_uai'], name: 'idx_zipcode')]
#[ORM\Index(columns: ['libelle_commune'], name: 'idx_city')]
#[ORM\Index(columns: ['denomination_principale'], name: 'idx_name')]
#[ORM\Index(columns: ['etat_etablissement'], name: 'idx_state')]
#[ORM\Index(columns: ['nature_uai'], name: 'idx_kind')]
#[ORM\Index(columns: ['secteur_public_prive_libe'], name: 'idx_private')]
class School
{
    #[ORM\Id]
    #[ORM\Column(length: 8)]
    private ?string $numero_uai;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $appellation_officielle = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $denomination_principale = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $patronyme_uai = null;

    #[ORM\Column(length: 10)]
    private string $secteur_public_prive_libe;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $adresse_uai = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lieu_dit_uai = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $boite_postale_uai = null;

    #[ORM\Column(length: 5)]
    private string $code_postal_uai;

    #[ORM\Column(length: 50)]
    private string $localite_acheminement_uai;

    #[ORM\Column(length: 100)]
    private string $libelle_commune;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $coordonnee_x = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $coordonnee_y = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $epsg = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $appariement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column]
    private int $nature_uai;

    #[ORM\Column(length: 100)]
    private string $nature_uai_libe;

    #[ORM\Column]
    private int $etat_etablissement;

    #[ORM\Column(length: 20)]
    private string $etat_etablissement_libe;

    #[ORM\Column(length: 3)]
    private string $code_departement;

    #[ORM\Column(length: 2)]
    private string $code_region;

    #[ORM\Column(length: 2)]
    private string $code_academie;

    #[ORM\Column(length: 5)]
    private string $code_commune;

    #[ORM\Column(length: 50)]
    private string $libelle_departement;

    #[ORM\Column(length: 50)]
    private string $libelle_region;

    #[ORM\Column(length: 50)]
    private string $libelle_academie;

    #[ORM\Column(type: "point", nullable: true)]
    private ?Point $position = null;

    #[ORM\Column(nullable: true)]
    private ?int $secteur_prive_code_type_contrat = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $secteur_prive_libelle_type_contrat = null;

    #[ORM\Column(length: 2)]
    private string $code_ministere;

    #[ORM\Column(length: 50)]
    private string $libelle_ministere;

    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date_ouverture;

    #[ORM\Column(type: Types::BIGINT)]
    private int $version = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?\DateTimeInterface $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable("now");
        $this->version++;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable("now");
    }

    public function getNumeroUai(): ?string
    {
        return $this->numero_uai;
    }

    public function setNumeroUai(?string $numero_uai): School
    {
        $this->numero_uai = $numero_uai;
        return $this;
    }

    public function getAppellationOfficielle(): ?string
    {
        return $this->appellation_officielle;
    }

    public function setAppellationOfficielle(?string $appellation_officielle): School
    {
        $this->appellation_officielle = $appellation_officielle;
        return $this;
    }

    public function getDenominationPrincipale(): ?string
    {
        return $this->denomination_principale;
    }

    public function setDenominationPrincipale(?string $denomination_principale): School
    {
        $this->denomination_principale = $denomination_principale;
        return $this;
    }

    public function getPatronymeUai(): ?string
    {
        return $this->patronyme_uai;
    }

    public function setPatronymeUai(?string $patronyme_uai): School
    {
        $this->patronyme_uai = $patronyme_uai;
        return $this;
    }

    public function getSecteurPublicPriveLibe(): string
    {
        return $this->secteur_public_prive_libe;
    }

    public function setSecteurPublicPriveLibe(string $secteur_public_prive_libe): School
    {
        $this->secteur_public_prive_libe = $secteur_public_prive_libe;
        return $this;
    }

    public function getAdresseUai(): ?string
    {
        return $this->adresse_uai;
    }

    public function setAdresseUai(?string $adresse_uai): School
    {
        $this->adresse_uai = $adresse_uai;
        return $this;
    }

    public function getLieuDitUai(): ?string
    {
        return $this->lieu_dit_uai;
    }

    public function setLieuDitUai(?string $lieu_dit_uai): School
    {
        $this->lieu_dit_uai = $lieu_dit_uai;
        return $this;
    }

    public function getBoitePostaleUai(): ?string
    {
        return $this->boite_postale_uai;
    }

    public function setBoitePostaleUai(?string $boite_postale_uai): School
    {
        $this->boite_postale_uai = $boite_postale_uai;
        return $this;
    }

    public function getCodePostalUai(): string
    {
        return $this->code_postal_uai;
    }

    public function setCodePostalUai(string $code_postal_uai): School
    {
        $this->code_postal_uai = $code_postal_uai;
        return $this;
    }

    public function getLocaliteAcheminementUai(): string
    {
        return $this->localite_acheminement_uai;
    }

    public function setLocaliteAcheminementUai(string $localite_acheminement_uai): School
    {
        $this->localite_acheminement_uai = $localite_acheminement_uai;
        return $this;
    }

    public function getLibelleCommune(): string
    {
        return $this->libelle_commune;
    }

    public function setLibelleCommune(string $libelle_commune): School
    {
        $this->libelle_commune = $libelle_commune;
        return $this;
    }

    public function getCoordonneeX(): ?float
    {
        return $this->coordonnee_x;
    }

    public function setCoordonneeX(?float $coordonnee_x): School
    {
        $this->coordonnee_x = $coordonnee_x;
        return $this;
    }

    public function getCoordonneeY(): ?float
    {
        return $this->coordonnee_y;
    }

    public function setCoordonneeY(?float $coordonnee_y): School
    {
        $this->coordonnee_y = $coordonnee_y;
        return $this;
    }

    public function getEpsg(): ?string
    {
        return $this->epsg;
    }

    public function setEpsg(?string $epsg): School
    {
        $this->epsg = $epsg;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): School
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): School
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getAppariement(): ?string
    {
        return $this->appariement;
    }

    public function setAppariement(?string $appariement): School
    {
        $this->appariement = $appariement;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): School
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getNatureUai(): int
    {
        return $this->nature_uai;
    }

    public function setNatureUai(int $nature_uai): School
    {
        $this->nature_uai = $nature_uai;
        return $this;
    }

    public function getNatureUaiLibe(): string
    {
        return $this->nature_uai_libe;
    }

    public function setNatureUaiLibe(string $nature_uai_libe): School
    {
        $this->nature_uai_libe = $nature_uai_libe;
        return $this;
    }

    public function getEtatEtablissement(): int
    {
        return $this->etat_etablissement;
    }

    public function setEtatEtablissement(int $etat_etablissement): School
    {
        $this->etat_etablissement = $etat_etablissement;
        return $this;
    }

    public function getEtatEtablissementLibe(): string
    {
        return $this->etat_etablissement_libe;
    }

    public function setEtatEtablissementLibe(string $etat_etablissement_libe): School
    {
        $this->etat_etablissement_libe = $etat_etablissement_libe;
        return $this;
    }

    public function getCodeDepartement(): string
    {
        return $this->code_departement;
    }

    public function setCodeDepartement(string $code_departement): School
    {
        $this->code_departement = $code_departement;
        return $this;
    }

    public function getCodeRegion(): string
    {
        return $this->code_region;
    }

    public function setCodeRegion(string $code_region): School
    {
        $this->code_region = $code_region;
        return $this;
    }

    public function getCodeAcademie(): string
    {
        return $this->code_academie;
    }

    public function setCodeAcademie(string $code_academie): School
    {
        $this->code_academie = $code_academie;
        return $this;
    }

    public function getCodeCommune(): string
    {
        return $this->code_commune;
    }

    public function setCodeCommune(string $code_commune): School
    {
        $this->code_commune = $code_commune;
        return $this;
    }

    public function getLibelleDepartement(): string
    {
        return $this->libelle_departement;
    }

    public function setLibelleDepartement(string $libelle_departement): School
    {
        $this->libelle_departement = $libelle_departement;
        return $this;
    }

    public function getLibelleRegion(): string
    {
        return $this->libelle_region;
    }

    public function setLibelleRegion(string $libelle_region): School
    {
        $this->libelle_region = $libelle_region;
        return $this;
    }

    public function getLibelleAcademie(): string
    {
        return $this->libelle_academie;
    }

    public function setLibelleAcademie(string $libelle_academie): School
    {
        $this->libelle_academie = $libelle_academie;
        return $this;
    }

    public function getPosition(): ?Point
    {
        return $this->position;
    }

    public function setPosition(?Point $position): School
    {
        $this->position = $position;
        return $this;
    }

    public function getSecteurPriveCodeTypeContrat(): ?int
    {
        return $this->secteur_prive_code_type_contrat;
    }

    public function setSecteurPriveCodeTypeContrat(?int $secteur_prive_code_type_contrat): School
    {
        $this->secteur_prive_code_type_contrat = $secteur_prive_code_type_contrat;
        return $this;
    }

    public function getSecteurPriveLibelleTypeContrat(): ?string
    {
        return $this->secteur_prive_libelle_type_contrat;
    }

    public function setSecteurPriveLibelleTypeContrat(?string $secteur_prive_libelle_type_contrat): School
    {
        $this->secteur_prive_libelle_type_contrat = $secteur_prive_libelle_type_contrat;
        return $this;
    }

    public function getCodeMinistere(): string
    {
        return $this->code_ministere;
    }

    public function setCodeMinistere(string $code_ministere): School
    {
        $this->code_ministere = $code_ministere;
        return $this;
    }

    public function getLibelleMinistere(): string
    {
        return $this->libelle_ministere;
    }

    public function setLibelleMinistere(string $libelle_ministere): School
    {
        $this->libelle_ministere = $libelle_ministere;
        return $this;
    }

    public function getDateOuverture(): \DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(\DateTimeInterface $date_ouverture): School
    {
        $this->date_ouverture = $date_ouverture;
        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): School
    {
        $this->version = $version;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
