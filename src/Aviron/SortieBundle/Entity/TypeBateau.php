<?php

namespace Aviron\SortieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeBateau
 *
 * @ORM\Table(name="gestion_aviron_bateau_type")
 * @ORM\Entity(repositoryClass="Aviron\SortieBundle\Repository\TypeBateauRepository")
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
}
