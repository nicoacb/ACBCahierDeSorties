<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gestion_aviron_membre_contacts")
 * @ORM\Entity(repositoryClass="App\Repository\MembreContactsRepository")
 */
class MembreContacts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $typeContact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomContact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephoneEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTypeContact(): ?int
    {
        return $this->typeContact;
    }

    public function setTypeContact(int $typeContact): self
    {
        $this->typeContact = $typeContact;

        return $this;
    }

    public function getNomContact(): ?string
    {
        return $this->nomContact;
    }

    public function setNomContact(?string $nomContact): self
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    public function getTelephoneEmail(): ?string
    {
        return $this->telephoneEmail;
    }

    public function setTelephoneEmail(?string $telephoneEmail): self
    {
        $this->telephoneEmail = $telephoneEmail;

        return $this;
    }
}
