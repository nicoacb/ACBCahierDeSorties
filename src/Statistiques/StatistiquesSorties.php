<?php

namespace App\Statistiques;

use App\Entity\Sortie;
use App\Statistiques\ModeleStatistiques;

class StatistiquesSorties
{
    private $repository;
    private $annee;
    private $mois;

    public function __construct($pRepository, $pAnnee, $pMois) {
        $this->repository = $pRepository;
        $this->annee = $pAnnee;
        $this->mois = $pMois;
    }

    public function getStatistiquesParMembre()
    {
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->repository->getSortiesTermineesStatistiques($this->annee, $this->mois);

        $statistiques = array();
        $maxSorties = 0;
        $maxKmParcourus = 0;
        foreach ($listSortiesTerminees as $sortie)
        {
            foreach  ($sortie->getAthletes() as $athlete)
            {
                if (!array_key_exists($athlete->getId(), $statistiques))
                {
                    $statistiques[$athlete->getId()] = new Statistique($athlete->getId(), $athlete->getPrenomNom());
                }
                $statistiques[$athlete->getId()]->ajouterSortie($sortie-> getKmparcourus());
                $maxKmParcourus = max($statistiques[$athlete->getId()]->getKmParcourus(), $maxKmParcourus);
                $maxSorties = max($statistiques[$athlete->getId()]->getNombreDeSorties(), $maxSorties);
            }
        }

        return new ModeleStatistiques($statistiques, $maxSorties, $maxKmParcourus);
    }

    public function getStatistiquesParBateau()
    {
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->repository->getSortiesTermineesStatistiques($this->annee, $this->mois);

        $statistiques = array();
        $maxSorties = 0;
        $maxKmParcourus = 0;
        foreach ($listSortiesTerminees as $sortie)
        {
            if (!array_key_exists($sortie->getBateau()->getId(), $statistiques))
            {
                $statistiques[$sortie->getBateau()->getId()] = new Statistique($sortie->getBateau()->getId(), $sortie->getBateau()->getTypeNom());
            }
            $statistiques[$sortie->getBateau()->getId()]->ajouterSortie($sortie-> getKmparcourus());
            $maxKmParcourus = max($statistiques[$sortie->getBateau()->getId()]->getKmParcourus(), $maxKmParcourus);
            $maxSorties = max($statistiques[$sortie->getBateau()->getId()]->getNombreDeSorties(), $maxSorties);
        }

        return new ModeleStatistiques($statistiques, $maxSorties, $maxKmParcourus);
    }
}