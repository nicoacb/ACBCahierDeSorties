<?php

namespace Aviron\SortieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="gestion_aviron_reservation")
 * @ORM\Entity(repositoryClass="Aviron\SortieBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @var \DateTime
     *
     * @ORM\Column(name="datereservation", type="datetime")
     */
    private $datereservation;

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
     * @ORM\ManyToOne(targetEntity="Aviron\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $idutsupp;

    /**
     * @var int
     *
     * @ORM\Column(name="idut", type="integer")
     * @ORM\ManyToOne(targetEntity="Aviron\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idut;

    /**
     * @var int
     *
     * @ORM\Column(name="identrainement", type="integer")
     * @ORM\ManyToOne(targetEntity="Aviron\SortieBundle\Entity\Bateau")
     * @ORM\JoinColumn(nullable=false)
     */
    private $identrainement;


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
     * Set datereservation
     *
     * @param \DateTime $datereservation
     *
     * @return Reservation
     */
    public function setDatereservation($datereservation)
    {
        $this->datereservation = $datereservation;

        return $this;
    }

    /**
     * Get datereservation
     *
     * @return \DateTime
     */
    public function getDatereservation()
    {
        return $this->datereservation;
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
     * @return Reservation
     */
    public function setIdutsupp($idutsupp)
    {
        $this->idutsupp = $idutsupp;

        return $this;
    }

    /**
     * Get idutsupp
     *
     * @return int
     */
    public function getIdutsupp()
    {
        return $this->idutsupp;
    }

    /**
     * Set idut
     *
     * @param integer $idut
     *
     * @return Reservation
     */
    public function setIdut($idut)
    {
        $this->idut = $idut;

        return $this;
    }

    /**
     * Get idut
     *
     * @return int
     */
    public function getIdut()
    {
        return $this->idut;
    }

    /**
     * Set identrainement
     *
     * @param integer $identrainement
     *
     * @return Reservation
     */
    public function setIdentrainement($identrainement)
    {
        $this->identrainement = $identrainement;

        return $this;
    }

    /**
     * Get identrainement
     *
     * @return int
     */
    public function getIdentrainement()
    {
        return $this->identrainement;
    }
}
