<?php

namespace Aviron\SortieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Sortie
 *
 * @ORM\Table(name="gestion_aviron_sortie")
 * @ORM\Entity(repositoryClass="Aviron\SortieBundle\Repository\SortieRepository")
 */
class Sortie
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
    * @ORM\ManyToOne(targetEntity="Aviron\SortieBundle\Entity\Bateau")
    * @ORM\JoinColumn(nullable=false)
    */
    private $bateau;

    /**
    * @ORM\ManyToMany(targetEntity="Aviron\UserBundle\Entity\User", cascade={"persist"})
    * @ORM\JoinTable(name="gestion_aviron_sortie_user")
    */
    private $athletes;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\Date()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hdepart", type="time")
     * @Assert\Time(message="L'heure de départ n'est pas une heure valide")
     */
    private $hdepart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hretour", type="time", nullable=true)
     * @Assert\Time(message="L'heure de retour n'est pas une heure valide")
     */
    private $hretour;

    /**
     * @var decimal
     *
     * @ORM\Column(name="kmparcourus", type="decimal", nullable=true, precision=4, scale=1)
     * @Assert\Range(min=0, minMessage="La distance doit être positive", invalidMessage="La donnée saisie pour la distance n'est pas valide")
     */
    private $kmparcourus;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", nullable=true)
     */
    private $observations;
    
    /**
    * @ORM\Column(name="datesupp", type="date", nullable=true)
    */
    private $datesupp;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Sortie
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set hdepart
     *
     * @param \DateTime $hdepart
     *
     * @return Sortie
     */
    public function setHdepart($hdepart)
    {
        $this->hdepart = $hdepart;

        return $this;
    }

    /**
     * Get hdepart
     *
     * @return \DateTime
     */
    public function getHdepart()
    {
        return $this->hdepart;
    }

    /**
     * Set hretour
     *
     * @param \DateTime $hretour
     *
     * @return Sortie
     */
    public function setHretour($hretour)
    {
        $this->hretour = $hretour;

        return $this;
    }

    /**
     * Get hretour
     *
     * @return \DateTime
     */
    public function getHretour()
    {
        return $this->hretour;
    }

    /**
     * Set kmparcourus
     *
     * @param float $kmparcourus
     *
     * @return Sortie
     */
    public function setKmparcourus($kmparcourus)
    {
        $this->kmparcourus = $kmparcourus;

        return $this;
    }

    /**
     * Get kmparcourus
     *
     * @return float
     */
    public function getKmparcourus()
    {
        return $this->kmparcourus;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return Sortie
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }
    
    /**
     * Set bateau
     *
     * @param \Aviron\SortieBundle\Entity\Bateau $bateau
     *
     * @return Sortie
     */
    public function setBateau(\Aviron\SortieBundle\Entity\Bateau $bateau)
    {
        $this->bateau = $bateau;

        return $this;
    }

    /**
     * Get bateau
     *
     * @return \Aviron\SortieBundle\Entity\Bateau
     */
    public function getBateau()
    {
        return $this->bateau;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->athletes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add athlete
     *
     * @param \Aviron\UserBundle\Entity\User $athlete
     *
     * @return Sortie
     */
    public function addAthlete(\Aviron\UserBundle\Entity\User $athlete)
    {
        $this->athletes[] = $athlete;

        return $this;
    }

    /**
     * Remove athlete
     *
     * @param \Aviron\UserBundle\Entity\User $athlete
     */
    public function removeAthlete(\Aviron\UserBundle\Entity\User $athlete)
    {
        $this->athletes->removeElement($athlete);
    }

    /**
     * Get athletes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAthletes()
    {
        return $this->athletes;
    }

    /**
    * @Assert\Callback
    */
    public function isAthletesValid(ExecutionContextInterface $context)
    {
        // On parcours chaque athlète pour vérifier
        $ids = array();
        foreach($this->athletes as $athlete)
        {
            if(in_array($athlete->getId(), $ids)) {
                // La règle est violée, on définit l'erreur
                $context
                    ->buildViolation($athlete->getPrenomNom().' ne peut pas occuper plusieurs places dans le même bateau.') 
                    ->atPath('athletes')
                    ->addViolation(); // ceci déclenche l'erreur, ne pas l'oublier

                break;
            } else {
                array_push($ids, $athlete->getId());
            }
        }
    }

    /**
     * Set datesupp
     *
     * @param \DateTime $datesupp
     *
     * @return Sortie
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
