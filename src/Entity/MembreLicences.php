<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gestion_aviron_membre_licences")
 * @ORM\Entity(repositoryClass="App\Repository\MembreLicencesRepository")
 */
class MembreLicences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $typeLicence;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $avecIASportPlus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCerficatPratique;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCertificatCompetition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Saison", inversedBy="licences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $saison;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="licences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateValidationInscription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     */
    private $userValidationInscription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSaisieLicence;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     */
    private $userSaisieLicence;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSuppressionInscription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     */
    private $userSuppressionInscription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $autoriseDroitImage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepteReglementInterieur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDemandeInscription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeLicence(): ?int
    {
        return $this->typeLicence;
    }

    public function setTypeLicence(int $typeLicence): self
    {
        $this->typeLicence = $typeLicence;

        return $this;
    }

    public function getAvecIASportPlus(): ?bool
    {
        return $this->avecIASportPlus;
    }

    public function setAvecIASportPlus(bool $avecIASportPlus): self
    {
        $this->avecIASportPlus = $avecIASportPlus;

        return $this;
    }

    public function getDateCerficatPratique(): ?\DateTimeInterface
    {
        return $this->dateCerficatPratique;
    }

    public function setDateCerficatPratique(?\DateTimeInterface $dateCerficatPratique): self
    {
        $this->dateCerficatPratique = $dateCerficatPratique;

        return $this;
    }

    public function getDateCertificatCompetition(): ?\DateTimeInterface
    {
        return $this->dateCertificatCompetition;
    }

    public function setDateCertificatCompetition(?\DateTimeInterface $dateCertificatCompetition): self
    {
        $this->dateCertificatCompetition = $dateCertificatCompetition;

        return $this;
    }

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(?Saison $saison): self
    {
        $this->saison = $saison;

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(?User $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getDateValidationInscription(): ?\DateTimeInterface
    {
        return $this->dateValidationInscription;
    }

    public function setDateValidationInscription(?\DateTimeInterface $dateValidationInscription): self
    {
        $this->dateValidationInscription = $dateValidationInscription;

        return $this;
    }

    public function getUserValidationInscription(): ?user
    {
        return $this->userValidationInscription;
    }

    public function setUserValidationInscription(?user $userValidationInscription): self
    {
        $this->userValidationInscription = $userValidationInscription;

        return $this;
    }

    public function getDateSaisieLicence(): ?\DateTimeInterface
    {
        return $this->dateSaisieLicence;
    }

    public function setDateSaisieLicence(?\DateTimeInterface $dateSaisieLicence): self
    {
        $this->dateSaisieLicence = $dateSaisieLicence;

        return $this;
    }

    public function getUserSaisieLicence(): ?user
    {
        return $this->userSaisieLicence;
    }

    public function setUserSaisieLicence(?user $userSaisieLicence): self
    {
        $this->userSaisieLicence = $userSaisieLicence;

        return $this;
    }

    public function getDateSuppressionInscription(): ?\DateTimeInterface
    {
        return $this->dateSuppressionInscription;
    }

    public function setDateSuppressionInscription(?\DateTimeInterface $dateSuppressionInscription): self
    {
        $this->dateSuppressionInscription = $dateSuppressionInscription;

        return $this;
    }

    public function getUserSuppressionInscription(): ?user
    {
        return $this->userSuppressionInscription;
    }

    public function setUserSuppressionInscription(?user $userSuppressionInscription): self
    {
        $this->userSuppressionInscription = $userSuppressionInscription;

        return $this;
    }

    public function getAutoriseDroitImage(): ?bool
    {
        return $this->autoriseDroitImage;
    }

    public function setAutoriseDroitImage(?bool $autoriseDroitImage): self
    {
        $this->autoriseDroitImage = $autoriseDroitImage;

        return $this;
    }

    public function getAccepteReglementInterieur(): ?bool
    {
        return $this->accepteReglementInterieur;
    }

    public function setAccepteReglementInterieur(?bool $accepteReglementInterieur): self
    {
        $this->accepteReglementInterieur = $accepteReglementInterieur;

        return $this;
    }

    public function getDateDemandeInscription(): ?\DateTimeInterface
    {
        return $this->dateDemandeInscription;
    }

    public function setDateDemandeInscription(?\DateTimeInterface $dateDemandeInscription): self
    {
        $this->dateDemandeInscription = $dateDemandeInscription;

        return $this;
    }
}
