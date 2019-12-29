<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeBateau
 *
 * @ORM\Table(name="gestion_aviron_bateau_type")
 * @ORM\Entity(repositoryClass="App\Repository\TypeBateauRepository")
 */
class TypeBateau
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
     * @ORM\Column(name="nom", type="string", length=10)
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
     * @var bool
     *
     * @ORM\Column(name="supp", type="boolean")
     */
    private $supp;


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
     * @return Type
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
     * Set supp
     *
     * @param boolean $supp
     *
     * @return Type
     */
    public function setSupp($supp)
    {
        $this->supp = $supp;

        return $this;
    }

    /**
     * Get supp
     *
     * @return bool
     */
    public function getSupp()
    {
        return $this->supp;
    }

    /**
     * Set nbplacerameurs
     *
     * @param integer $nbplacerameurs
     *
     * @return TypeBateau
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
     * @return TypeBateau
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
}
