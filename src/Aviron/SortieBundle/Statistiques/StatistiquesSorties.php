<?php

namespace Aviron\SortieBundle\Statistiques;

use Aviron\SortieBundle\Entity\Sortie;
use Aviron\SortieBundle\Statistiques\ModeleStatistiques;

class StatistiquesSorties
{
    private $repository;

    public function __construct($pRepository) {
        $this->repository = $pRepository;
    }

    public function getStatistiquesParMembre()
    {
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->repository->getSortiesTermineesStatistiques();

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
        $listSortiesTerminees = $this->repository->getSortiesTermineesStatistiques();

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