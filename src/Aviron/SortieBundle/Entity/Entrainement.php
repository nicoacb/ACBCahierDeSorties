<?php

namespace Aviron\SortieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrainement
 *
 * @ORM\Table(name="gestion_aviron_entrainement")
 * @ORM\Entity(repositoryClass="Aviron\SortieBundle\Repository\EntrainementRepository")
 */
class Entrainement
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date")
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="date", nullable=true)
     */
    private $datefin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heuredebut", type="time")
     */
    private $heuredebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heurefin", type="time")
     */
    private $heurefin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecloture", type="datetime", nullable=true)
     */
    private $datecloture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesupp", type="datetime", nullable=true)
     */
    private $datesupp;

    /**
     * @var int
     *
     * @ORM\Column(name="idutsupp", type="integer", nullable=true)
     */
    private $idutsupp;

    /**
     * @var int
     *
     * @ORM\Column(name="nbplacesdisponibles", type="integer")
     */
    private $nbplacesdisponibles;

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
     * @return Reservation
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
     * Set datedebut
     *
     * @param \DateTime $datedebut
     *
     * @return Reservation
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     *
     * @return Reservation
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set heuredebut
     *
     * @param \DateTime $heuredebut
     *
     * @return Reservation
     */
    public function setHeuredebut($heuredebut)
    {
        $this->heuredebut = $heuredebut;

        return $this;
    }

    /**
     * Get heuredebut
     *
     * @return \DateTime
     */
    public function getHeuredebut()
    {
        return $this->heuredebut;
    }

    /**
     * Set heurefin
     *
     * @param \DateTime $heurefin
     *
     * @return Reservation
     */
    public function setHeurefin($heurefin)
    {
        $this->heurefin = $heurefin;

        return $this;
    }

    /**
     * Get heurefin
     *
     * @return \DateTime
     */
    public function getHeurefin()
    {
        return $this->heurefin;
    }

    /**
     * Set nbplacesdisponibles
     *
     * @param integer $nbplacesdisponibles
     *
     * @return Reservation
     */
    public function setNbplacesdisponibles($nbplacesdisponibles)
    {
        $this->nbplacesdisponibles = $nbplacesdisponibles;

        return $this;
    }

    /**
     * Get nbplacesdisponibles
     *
     * @return int
     */
    public function getNbplacesdisponibles()
    {
        return $this->nbplacesdisponibles;
    }

    /**
     * Set datecloture
     *
     * @param \DateTime $datecloture
     *
     * @return Reservation
     */
    public function setDatecloture($datecloture)
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    /**
     * Get datecloture
     *
     * @return \DateTime
     */
    public function getDatecloture()
    {
        return $this->datecloture;
    }

    /**
     * Set datesupp
     *
     * @param \DateTime $datesupp
     *
     * @return Reservation
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
     * Set idutsupp
     *
     * @param integer $idutsupp
     *
     * @return Entrainement
     */
    public function setIdutsupp($idutsupp)
    {
        $this->idutsupp = $idutsupp;

        return $this;
    }

    /**
     * Get idutsupp
     *
     * @return integer
     */
    public function getIdutsupp()
    {
        return $this->idutsupp;
    }
}
