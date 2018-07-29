<?php

namespace Aviron\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="gestion_aviron_user")
 * @ORM\Entity(repositoryClass="Aviron\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="civilite", type="integer", nullable=true)

     */
    private $civilite;
    
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
     * @ORM\Column(name="datedenaissance", type="date", nullable=true)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre date de naissance.", groups={"Registration", "Profile"})
     */
    private $datedenaissance;
    
    /**
     * @ORM\Column(name="nationalite", type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le nom de la nationalité est trop court.",
     *     maxMessage="Le nom de la nationalité est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $nationalite;
    
    /**
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre adresse.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="L'adresse est trop courte.",
     *     maxMessage="L'adresse est trop longue.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $adresse;
    
    /**
     * @ORM\Column(name="adresse2", type="string", length=255, nullable=true)
     */
    private $adresse2;
    
    /**
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre ville.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le nom de la ville est trop court.",
     *     maxMessage="Le nom de la ville est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $ville;
    
    /**
     * @ORM\Column(name="codepostal", type="integer", nullable=true)
     *
     * @Assert\NotBlank(message="Merci de renseigner votre code  postal.", groups={"Registration", "Profile"})
     */
    private $codepostal;

    /**
     * @ORM\Column(name="etablissementscolaire", type="string", length=255, nullable=true)
     */
    private $etablissementscolaire;
    
    /**
     * @ORM\Column(name="classe", type="string", length=255, nullable=true)
     */
    private $classe;

     /**
     * @ORM\Column(name="canrow", type="boolean", nullable=true)
     */
    private $canrow;

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
        return $this->nom;
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
        return $this->prenom;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codepostal
     *
     * @param integer $codepostal
     *
     * @return User
     */
    public function setCodepostal($codepostal)
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * Get codepostal
     *
     * @return integer
     */
    public function getCodepostal()
    {
        return $this->codepostal;
    }

    /**
     * Set datedenaissance
     *
     * @param \DateTime $datedenaissance
     *
     * @return User
     */
    public function setDatedenaissance($datedenaissance)
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    /**
     * Get datedenaissance
     *
     * @return \DateTime
     */
    public function getDatedenaissance()
    {
        return $this->datedenaissance;
    }

    /**
     * Set nationalite
     *
     * @param string $nationalite
     *
     * @return User
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set adresse2
     *
     * @param string $adresse2
     *
     * @return User
     */
    public function setAdresse2($adresse2)
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    /**
     * Get adresse2
     *
     * @return string
     */
    public function getAdresse2()
    {
        return $this->adresse2;
    }

    /**
     * Set etablissementscolaire
     *
     * @param string $etablissementscolaire
     *
     * @return User
     */
    public function setEtablissementscolaire($etablissementscolaire)
    {
        $this->etablissementscolaire = $etablissementscolaire;

        return $this;
    }

    /**
     * Get etablissementscolaire
     *
     * @return string
     */
    public function getEtablissementscolaire()
    {
        return $this->etablissementscolaire;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return User
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set canrow
     *
     * @param boolean $canrow
     *
     * @return User
     */
    public function setCanRow($canrow)
    {
        $this->canrow = $canrow;

        return $this;
    }

    /**
     * Get canrow
     *
     * @return boolean
     */
    public function getCanrow()
    {
        return $this->canrow;
    }

    /**
     * Set civilite
     *
     * @param integer $civilite
     *
     * @return User
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return integer
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Get PrenomNom
     *
     * @return string
     */
    public function getPrenomNom()
    {
        return $this->prenom.' '.$this->nom;
    }
}
