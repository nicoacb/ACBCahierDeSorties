<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gestion_aviron_membre_frequence_pratique")
 * @ORM\Entity(repositoryClass="App\Repository\FrequencePratiqueRepository")
 */
class FrequencePratique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $frequence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user", mappedBy="frequencePratique")
     */
    private $membre;

    public function __construct()
    {
        $this->membre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrequence(): ?string
    {
        return $this->frequence;
    }

    public function setFrequence(string $frequence): self
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getMembre(): Collection
    {
        return $this->membre;
    }

    public function addMembre(user $membre): self
    {
        if (!$this->membre->contains($membre)) {
            $this->membre[] = $membre;
            $membre->setFrequencePratique($this);
        }

        return $this;
    }

    public function removeMembre(user $membre): self
    {
        if ($this->membre->contains($membre)) {
            $this->membre->removeElement($membre);
            // set the owning side to null (unless already changed)
            if ($membre->getFrequencePratique() === $this) {
                $membre->setFrequencePratique(null);
            }
        }

        return $this;
    }
}
