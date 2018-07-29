<?php

namespace Aviron\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Licence
 *
 * @ORM\Table(name="gestion_aviron_licence")
 * @ORM\Entity(repositoryClass="Aviron\UserBundle\Repository\LicenceRepository")
 */
class Licence
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
     * @var date
     *
     * @ORM\Column(name="datesaisie", type="date")
     */
    private $datesaisie;
    
    /**
    * @ORM\ManyToOne(targetEntity="Aviron\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;
    
    /**
    * @ORM\ManyToOne(targetEntity="Aviron\UserBundle\Entity\TypeLicence")
    * @ORM\JoinColumn(nullable=false)
    */
    private $typelicence;

    /**
    * @ORM\ManyToOne(targetEntity="Aviron\SortieBundle\Entity\Saison")
    * @ORM\JoinColumn(nullable=false)
    */
    private $saison;
    
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
     * @return Licence
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
     * Set datesaisie
     *
     * @param \DateTime $datesaisie
     *
     * @return Licence
     */
    public function setDatesaisie($datesaisie)
    {
        $this->datesaisie = $datesaisie;

        return $this;
    }

    /**
     * Get datesaisie
     *
     * @return \DateTime
     */
    public function getDatesaisie()
    {
        return $this->datesaisie;
    }

    /**
     * Set user
     *
     * @param \Aviron\UserBundle\Entity\User $user
     *
     * @return Licence
     */
    public function setUser(\Aviron\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Aviron\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set typelicence
     *
     * @param \Aviron\UserBundle\Entity\TypeLicence $typelicence
     *
     * @return Licence
     */
    public function setTypelicence(\Aviron\UserBundle\Entity\TypeLicence $typelicence)
    {
        $this->typelicence = $typelicence;

        return $this;
    }

    /**
     * Get typelicence
     *
     * @return \Aviron\UserBundle\Entity\TypeLicence
     */
    public function getTypelicence()
    {
        return $this->typelicence;
    }

    /**
     * Set saison
     *
     * @param \Aviron\SortieBundle\Entity\Saison $saison
     *
     * @return Licence
     */
    public function setSaison(\Aviron\SortieBundle\Entity\Saison $saison)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return \Aviron\SortieBundle\Entity\Saison
     */
    public function getSaison()
    {
        return $this->saison;
    }
}
