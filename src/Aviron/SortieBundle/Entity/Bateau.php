<?php

namespace Aviron\SortieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aviron\SortieBundle\Entity\TypeBateau;

/**
 * Bateau
 *
 * @ORM\Table(name="gestion_aviron_bateau")
 * @ORM\Entity(repositoryClass="Aviron\SortieBundle\Repository\BateauRepository")
 */
class Bateau
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
    * @ORM\ManyToOne(targetEntity="Aviron\SortieBundle\Entity\TypeBateau", cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="nbplacerameurs", type="integer")
     */
    private $nbplacerameurs;

    /**
     * @var int
     *
     * @ORM\Column(name="nbplacebarreurs", type="integer")
     */
    private $nbplacebarreurs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefabrication", type="date", nullable=true)
     */
    private $datefabrication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateachat", type="date", nullable=true)
     */
    private $dateachat;

    /**
     * @var string
     *
     * @ORM\Column(name="fabriquant", type="string", length=50, nullable=true)
     */
    private $fabriquant;

    /**
     * @var string
     *
     * @ORM\Column(name="gamme", type="string", length=50, nullable=true)
     */
    private $gamme;
    
    /**
    * @ORM\Column(name="supp", type="boolean")
    */
    private $supp = false;
    
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
     * @return Bateau
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
     * Set nbplacerameurs
     *
     * @param integer $nbplacerameurs
     *
     * @return Bateau
     */
    public function setNbplacerameurs($nbplacerameurs)
    {
        $this->nbplacerameurs = $nbplacerameurs;

        return $this;
    }

    /**
     * Get nbplacerameurs
     *
     * @return integer
     */
    public function getNbplacerameurs()
    {
        return $this->nbplacerameurs;
    }

    /**
     * Set nbplacebarreurs
     *
     * @param integer $nbplacebarreurs
     *
     * @return Bateau
     */
    public function setNbplacebarreurs($nbplacebarreurs)
    {
        $this->nbplacebarreurs = $nbplacebarreurs;

        return $this;
    }

    /**
     * Get nbplacebarreurs
     *
     * @return integer
     */
    public function getNbplacebarreurs()
    {
        return $this->nbplacebarreurs;
    }

    /**
     * Set datefabrication
     *
     * @param \DateTime $datefabrication
     *
     * @return Bateau
     */
    public function setDatefabrication($datefabrication)
    {
        $this->datefabrication = $datefabrication;

        return $this;
    }

    /**
     * Get datefabrication
     *
     * @return \DateTime
     */
    public function getDatefabrication()
    {
        return $this->datefabrication;
    }

    /**
     * Set dateachat
     *
     * @param \DateTime $dateachat
     *
     * @return Bateau
     */
    public function setDateachat($dateachat)
    {
        $this->dateachat = $dateachat;

        return $this;
    }

    /**
     * Get dateachat
     *
     * @return \DateTime
     */
    public function getDateachat()
    {
        return $this->dateachat;
    }

    /**
     * Set fabriquant
     *
     * @param string $fabriquant
     *
     * @return Bateau
     */
    public function setFabriquant($fabriquant)
    {
        $this->fabriquant = $fabriquant;

        return $this;
    }

    /**
     * Get fabriquant
     *
     * @return string
     */
    public function getFabriquant()
    {
        return $this->fabriquant;
    }

    /**
     * Set gamme
     *
     * @param string $gamme
     *
     * @return Bateau
     */
    public function setGamme($gamme)
    {
        $this->gamme = $gamme;

        return $this;
    }

    /**
     * Get gamme
     *
     * @return string
     */
    public function getGamme()
    {
        return $this->gamme;
    }

    /**
     * Set supp
     *
     * @param boolean $supp
     *
     * @return Bateau
     */
    public function setSupp($supp)
    {
        $this->supp = $supp;

        return $this;
    }

    /**
     * Get supp
     *
     * @return boolean
     */
    public function getSupp()
    {
        return $this->supp;
    }

    /**
     * Set type
     *
     * @param \Aviron\SortieBundle\Entity\TypeBateau $type
     *
     * @return Bateau
     */
    public function setType(\Aviron\SortieBundle\Entity\TypeBateau $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Aviron\SortieBundle\Entity\TypeBateau
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeNom()
    {
        return $this->type->getNom().' '.$this->nom;
    }

}
