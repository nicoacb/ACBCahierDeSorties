<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gestion_aviron_membre_envies_pratique")
 * @ORM\Entity(repositoryClass="App\Repository\EnviesPratiqueRepository")
 */
class EnviesPratique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="enviesPratiques", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="gestion_aviron_envies_pratique_membre")
     */
    private $membre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $envie;

    public function __construct()
    {
        $this->membre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembre(): Collection
    {
        return $this->membre;
    }

    public function addMembre(User $membre): self
    {
        if (!$this->membre->contains($membre)) {
            $this->membre[] = $membre;
        }

        $membre->addEnviesPratique($this);

        return $this;
    }

    public function removeMembre(User $membre): self
    {
        if ($this->membre->contains($membre)) {
            $this->membre->removeElement($membre);
        }

        return $this;
    }

    public function getEnvie(): ?string
    {
        return $this->envie;
    }

    public function setEnvie(string $envie): self
    {
        $this->envie = $envie;

        return $this;
    }
}
