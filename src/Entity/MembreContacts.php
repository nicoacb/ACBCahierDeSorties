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
     * @ORM\Column(type="string", length=10)
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $typeContact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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
}
