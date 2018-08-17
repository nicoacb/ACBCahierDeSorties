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
    * @ORM\JoinColumn(nullable=false)
    */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

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
    * @ORM\Column(name="datesupp", type="date", nullable=true)
    */
    private $datesupp;

    /**
     * @ORM\Column(name="datehorsservice", type="date", nullable=true)
     */
    private $datehorsservice;
    
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


    /**
     * Set datehorsservice
     *
     * @param \DateTime $datehorsservice
     *
     * @return Bateau
     */
    public function setDatehorsservice($datehorsservice)
    {
        $this->datehorsservice = $datehorsservice;

        return $this;
    }

    /**
     * Get datehorsservice
     *
     * @return \DateTime
     */
    public function getDatehorsservice()
    {
        return $this->datehorsservice;
    }

    /**
     * Set datesupp
     *
     * @param \DateTime $datesupp
     *
     * @return Bateau
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
}
