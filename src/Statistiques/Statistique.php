<?php

namespace App\Statistiques;

class Statistique
{
    private $id;
    private $kmParcourus;
    private $label;
    private $nombreDeSorties;

    function __construct($pId, $pLabel)
    {
        $this->id = $pId;
        $this->label = $pLabel;
        $this->kmParcourus = 0;
        $this->nombreDeSorties = 0;
    }

    function ajouterSortie($pKmParcourus)
    {
        $this->nombreDeSorties++;
        $this->kmParcourus += $pKmParcourus;
    }

    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
    }

    function getKmParcourus()
    {
        return $this->kmParcourus;
    }

    function getNombreDeSorties()
    {
        return $this->nombreDeSorties;
    }
}