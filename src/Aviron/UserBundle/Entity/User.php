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
    * @ORM\ManyToMany(targetEntity="Aviron\SortieBundle\Entity\Saison", cascade={"persist"})
    * @ORM\JoinTable(name="gestion_aviron_saison_user")
    */
    private $saisons;
    
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
     * Get PrenomNom
     *
     * @return string
     */
    public function getPrenomNom()
    {
        return $this->prenom.' '.$this->nom;
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

    /**
     * Add saison
     *
     * @param \Aviron\SortieBundle\Entity\Saison $saison
     *
     * @return User
     */
    public function addSaison(\Aviron\SortieBundle\Entity\Saison $saison)
    {
        $this->saisons[] = $saison;

        return $this;
    }

    /**
     * Remove saison
     *
     * @param \Aviron\SortieBundle\Entity\Saison $saison
     */
    public function removeSaison(\Aviron\SortieBundle\Entity\Saison $saison)
    {
        $this->saisons->removeElement($saison);
    }

    /**
     * Get saisons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSaisons()
    {
        return $this->saisons;
    }
}
