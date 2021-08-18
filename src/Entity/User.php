<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * User
 *
 * @ORM\Table(name="gestion_aviron_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields={"username"},
 *  message="Un membre utilise déjà ce login, merci d'en choisir un autre",
 *  groups={"flow_preinscription_step1", "flow_reinscription_step0"}
 * )
 */
class User implements UserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->enabled = false;
        $this->contacts = new ArrayCollection();
        $this->licences = new ArrayCollection();
        $this->enviesPratiques = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre nom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=255)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre prénom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(name="datesupp", type="date", nullable=true)
     */
    private $datesupp;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $password_requested_at;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Email(
     *     message = "{{ value }} n'est pas une adresse email valide.",
     *     groups={"flow_preinscription_step3", "flow_reinscription_step2"}
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $licence;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $numeroVoie;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $typeVoie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelleVoie;

    /**
     * @ORM\Column(type="string", length=38, nullable=true)
     */
    private $immBatRes;

    /**
     * @ORM\Column(type="string", length=38, nullable=true)
     */
    private $aptEtageEsc;

    /**
     * @ORM\Column(type="string", length=38, nullable=true)
     */
    private $lieuDit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=33, nullable=true)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MembreContacts", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MembreLicences", mappedBy="membre", orphanRemoval=true, cascade={"persist"})
     */
    private $licences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entrepriseProfessionEcole;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EnviesPratique", inversedBy="membre")
     * @ORM\JoinTable(name="gestion_aviron_envies_pratique_membre")
     */
    private $enviesPratiques;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\EngagementAssociation", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $engagementAssociation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MembreNationalite")
     */
    private $nationalite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FrequencePratique", inversedBy="membre")
     */
    private $frequencePratique;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $reinscriptionToken;

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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
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
        return mb_convert_case($this->nom, MB_CASE_TITLE);
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return mb_convert_case($this->prenom, MB_CASE_TITLE);
    }

    /**
     * Get PrenomNom
     *
     * @return string
     */
    public function getPrenomNom()
    {
        return mb_convert_case($this->prenom . ' ' . $this->nom, MB_CASE_TITLE);
    }

    /**
     * Set datesupp
     *
     * @param \DateTime $datesupp
     *
     * @return User
     */
    public function setDatesupp($datesupp)
    {
        $this->datesupp = $datesupp;

        return $this;
    }

    /**
     * Get datesupp
     *
     * @return \DateTime
     */
    public function getDatesupp()
    {
        return $this->datesupp;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPasswordRequestedAt(): ?\DateTimeInterface
    {
        return $this->password_requested_at;
    }

    public function setPasswordRequestedAt(?\DateTimeInterface $password_requested_at): self
    {
        $this->password_requested_at = $password_requested_at;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set licence
     *
     * @param int $licence
     *
     * @return User
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return int
     */
    public function getLicence()
    {
        return $this->licence;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getCivilite(): ?int
    {
        return $this->civilite;
    }

    public function setCivilite(?int $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNumeroVoie(): ?string
    {
        return $this->numeroVoie;
    }

    public function setNumeroVoie(?string $numeroVoie): self
    {
        $this->numeroVoie = $numeroVoie;

        return $this;
    }

    public function getTypeVoie(): ?string
    {
        return $this->typeVoie;
    }

    public function setTypeVoie(?string $typeVoie): self
    {
        $this->typeVoie = $typeVoie;

        return $this;
    }

    public function getLibelleVoie(): ?string
    {
        return $this->libelleVoie;
    }

    public function setLibelleVoie(?string $libelleVoie): self
    {
        $this->libelleVoie = $libelleVoie;

        return $this;
    }

    public function getImmBatRes(): ?string
    {
        return $this->immBatRes;
    }

    public function setImmBatRes(?string $immBatRes): self
    {
        $this->immBatRes = $immBatRes;

        return $this;
    }

    public function getAptEtageEsc(): ?string
    {
        return $this->aptEtageEsc;
    }

    public function setAptEtageEsc(?string $aptEtageEsc): self
    {
        $this->aptEtageEsc = $aptEtageEsc;

        return $this;
    }

    public function getLieuDit(): ?string
    {
        return $this->lieuDit;
    }

    public function setLieuDit(?string $lieuDit): self
    {
        $this->lieuDit = $lieuDit;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|MembreContacts[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(MembreContacts $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(MembreContacts $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
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
            $licence->setMembre($this);
        }

        return $this;
    }

    public function removeLicence(MembreLicences $licence): self
    {
        if ($this->licences->contains($licence)) {
            $this->licences->removeElement($licence);
            // set the owning side to null (unless already changed)
            if ($licence->getMembre() === $this) {
                $licence->setMembre(null);
            }
        }

        return $this;
    }

    public function getEntrepriseProfessionEcole(): ?string
    {
        return $this->entrepriseProfessionEcole;
    }

    public function setEntrepriseProfessionEcole(?string $entrepriseProfessionEcole): self
    {
        $this->entrepriseProfessionEcole = $entrepriseProfessionEcole;

        return $this;
    }

    /**
     * @return Collection|EnviesPratique[]
     */
    public function getEnviesPratiques(): Collection
    {
        return $this->enviesPratiques;
    }

    public function addEnviesPratique(EnviesPratique $enviesPratique): self
    {
        if (!$this->enviesPratiques->contains($enviesPratique)) {
            $this->enviesPratiques[] = $enviesPratique;
            $enviesPratique->addMembre($this);
        }

        return $this;
    }

    public function removeEnviesPratique(EnviesPratique $enviesPratique): self
    {
        if ($this->enviesPratiques->contains($enviesPratique)) {
            $this->enviesPratiques->removeElement($enviesPratique);
            $enviesPratique->removeMembre($this);
        }

        return $this;
    }

    public function getEngagementAssociation(): ?EngagementAssociation
    {
        return $this->engagementAssociation;
    }

    public function setEngagementAssociation(EngagementAssociation $engagementAssociation): self
    {
        $this->engagementAssociation = $engagementAssociation;

        // set the owning side of the relation if necessary
        if ($engagementAssociation->getMembre() !== $this) {
            $engagementAssociation->setMembre($this);
        }

        return $this;
    }

    public function getNationalite(): ?MembreNationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?MembreNationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @Assert\Callback(groups={"flow_preinscription_step2"})
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (($this->getLibelleVoie() == null || $this->getLibelleVoie() == '') && ($this->getLieuDit() == null || $this->getLieuDit() == '')) {
            $context->buildViolation('L\'adresse est un champ obligatoire.')
                ->atPath('libelleVoie')
                ->addViolation();
        }
        if (($this->getLibelleVoie() != null || $this->getTypeVoie() != null)  && $this->getNumeroVoie() == null) {
            $context->buildViolation('Le numéro de la voie est obligatoire.')
                ->atPath('numeroVoie')
                ->addViolation();
        }
        if (($this->getNumeroVoie() != null || $this->getTypeVoie() != null)  && $this->getLibelleVoie() == null) {
            $context->buildViolation('Le nom de la voie est obligatoire.')
                ->atPath('libelleVoie')
                ->addViolation();
        }
        if (($this->getNumeroVoie() != null || $this->getLibelleVoie() != null)  && $this->getTypeVoie() == null) {
            $context->buildViolation('Le type de voie est obligatoire.')
                ->atPath('typeVoie')
                ->addViolation();
        }
    }

    public function getFrequencePratique(): ?FrequencePratique
    {
        return $this->frequencePratique;
    }

    public function setFrequencePratique(?FrequencePratique $frequencePratique): self
    {
        $this->frequencePratique = $frequencePratique;

        return $this;
    }

    public function getReinscriptionToken(): ?string
    {
        return $this->reinscriptionToken;
    }

    public function setReinscriptionToken(?string $reinscriptionToken): self
    {
        $this->reinscriptionToken = $reinscriptionToken;

        return $this;
    }
}
