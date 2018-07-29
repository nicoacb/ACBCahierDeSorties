<?php

namespace Aviron\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="gestion_aviron_categorie")
 * @ORM\Entity(repositoryClass="Aviron\UserBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneedebut", type="date")
     */
    private $anneedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneefin", type="date")
     */
    private $anneefin;


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
     * @return Categorie
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
     * Set anneedebut
     *
     * @param \DateTime $anneedebut
     *
     * @return Categorie
     */
    public function setAnneedebut($anneedebut)
    {
        $this->anneedebut = $anneedebut;

        return $this;
    }

    /**
     * Get anneedebut
     *
     * @return \DateTime
     */
    public function getAnneedebut()
    {
        return $this->anneedebut;
    }

    /**
     * Set anneefin
     *
     * @param \DateTime $anneefin
     *
     * @return Categorie
     */
    public function setAnneefin($anneefin)
    {
        $this->anneefin = $anneefin;

        return $this;
    }

    /**
     * Get anneefin
     *
     * @return \DateTime
     */
    public function getAnneefin()
    {
        return $this->anneefin;
    }
}

