<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Saison
 *
 * @ORM\Table(name="gestion_aviron_saison")
 * @ORM\Entity(repositoryClass="App\Repository\SaisonRepository")
 */
class Saison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="courante", type="boolean", length=25)
     */
    private $courante;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MembreLicences", mappedBy="saison", orphanRemoval=true)
     */
    private $licences;

    public function __construct()
    {
        $this->licences = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Saison
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set courante
     *
     * @param boolean $courante
     *
     * @return Saison
     */
    public function setCourante($courante)
    {
        $this->courante = $courante;

        return $this;
    }

    /**
     * Get courante
     *
     * @return boolean
     */
    public function getCourante()
    {
        return $this->courante;
    }

    /**
     * @return Collection|MembreLicences[]
     */
    public function getLicences(): Collection
    {
        return $this->licences;
    }

    public function addLicence(MembreLicences $licence): self
    {
        if (!$this->licences->contains($licence)) {
            $this->licences[] = $licence;
            $licence->setSaison($this);
        }

        return $this;
    }

    public function removeLicence(MembreLicences $licence): self
    {
        if ($this->licences->contains($licence)) {
            $this->licences->removeElement($licence);
            // set the owning side to null (unless already changed)
            if ($licence->getSaison() === $this) {
                $licence->setSaison(null);
            }
        }

        return $this;
    }
}
