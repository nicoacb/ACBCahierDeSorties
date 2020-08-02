<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gestion_aviron_membre_engagement_association")
 * @ORM\Entity(repositoryClass="App\Repository\EngagementAssociationRepository")
 */
class EngagementAssociation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aPermisBateauEauxInterieures;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aPermisBateauCotier;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aPermisRemorqueEB;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aPermisRemorqueB96;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brevetSecourisme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $encadrementSportif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $communication;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $informatique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autres;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="engagementAssociation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAPermisBateauEauxInterieures(): ?bool
    {
        return $this->aPermisBateauEauxInterieures;
    }

    public function setAPermisBateauEauxInterieures(?bool $aPermisBateauEauxInterieures): self
    {
        $this->aPermisBateauEauxInterieures = $aPermisBateauEauxInterieures;

        return $this;
    }

    public function getAPermisBateauCotier(): ?bool
    {
        return $this->aPermisBateauCotier;
    }

    public function setAPermisBateauCotier(?bool $aPermisBateauCotier): self
    {
        $this->aPermisBateauCotier = $aPermisBateauCotier;

        return $this;
    }

    public function getAPermisRemorqueEB(): ?bool
    {
        return $this->aPermisRemorqueEB;
    }

    public function setAPermisRemorqueEB(?bool $aPermisRemorqueEB): self
    {
        $this->aPermisRemorqueEB = $aPermisRemorqueEB;

        return $this;
    }

    public function getAPermisRemorqueB96(): ?bool
    {
        return $this->aPermisRemorqueB96;
    }

    public function setAPermisRemorqueB96(?bool $aPermisRemorqueB96): self
    {
        $this->aPermisRemorqueB96 = $aPermisRemorqueB96;

        return $this;
    }

    public function getBrevetSecourisme(): ?string
    {
        return $this->brevetSecourisme;
    }

    public function setBrevetSecourisme(?string $brevetSecourisme): self
    {
        $this->brevetSecourisme = $brevetSecourisme;

        return $this;
    }

    public function getEncadrementSportif(): ?string
    {
        return $this->encadrementSportif;
    }

    public function setEncadrementSportif(?string $encadrementSportif): self
    {
        $this->encadrementSportif = $encadrementSportif;

        return $this;
    }

    public function getCommunication(): ?string
    {
        return $this->communication;
    }

    public function setCommunication(?string $communication): self
    {
        $this->communication = $communication;

        return $this;
    }

    public function getInformatique(): ?string
    {
        return $this->informatique;
    }

    public function setInformatique(?string $informatique): self
    {
        $this->informatique = $informatique;

        return $this;
    }

    public function getTechnique(): ?string
    {
        return $this->technique;
    }

    public function setTechnique(?string $technique): self
    {
        $this->technique = $technique;

        return $this;
    }

    public function getAutres(): ?string
    {
        return $this->autres;
    }

    public function setAutres(?string $autres): self
    {
        $this->autres = $autres;

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(User $membre): self
    {
        $this->membre = $membre;

        return $this;
    }
}
