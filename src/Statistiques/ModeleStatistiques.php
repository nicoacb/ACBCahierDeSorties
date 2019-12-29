<?php

namespace App\Statistiques;

class ModeleStatistiques
{
    private $statistiques;
    private $maxSorties;
    private $maxKmParcourus;

    function __construct($pStatistiques, $pMaxSorties, $pMaxKmParcourus)
    {
        $this->statistiques = $pStatistiques;
        $this->maxSorties = $pMaxSorties;
        $this->maxKmParcourus = $pMaxKmParcourus;
    }

    function getStatistiques()
    {
        return $this->statistiques;
    }

    function getMaxSorties()
    {
        return $this->maxSorties;
    }

    function getMaxKmParcourus()
    {
        return $this->maxKmParcourus;
    }
}