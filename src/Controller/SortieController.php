<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Form\SortieAddType;
use App\Form\SortieEndType;
use App\Statistiques\StatistiquesSorties;
use App\Entity\User;
use App\Repository\BateauRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SortieController extends AbstractController
{
    private $entityManager;
    private $sortieRepository;
    private $bateauRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository, 
        BateauRepository $bateauRepository,
        UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->sortieRepository = $sortieRepository;
        $this->bateauRepository = $bateauRepository;
        $this->userRepository = $userRepository;
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function accueil()
    {
        return $this->render('sortie/accueil.html.twig');
    }

    /**
    * @Security("has_role('ROLE_SORTIES')")
    */
    public function index($page)
    {
        // On ne sait pas combien de pages il y a mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher une page d'erreur 404
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Nombre de sorties à afficher par page
        $nbParPage = $this->getParameter('nbSortiesParPage');

        // On récupère la liste des sorties en cours si on est sur la première page
        $listsorties = NULL;
        if ($page == 1) {
            $listsorties = $this->sortieRepository
                ->getSortiesEnCours();
        }
        
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->sortieRepository
    	    ->getSortiesTerminees($page, $nbParPage);

        // On calcule le nombre total de pages grâces au count($listSortiesTerminees) qui retourne 
        // le nombre total de sorties terminées
        $nbPages = ceil(count($listSortiesTerminees) / $nbParPage);
		 
        // Si la page n'existe pas, on lève une erreur 404
        if($page != 1 && $page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('sortie/index.html.twig', 
            array(
                'listSorties' => $listsorties,
                'listSortiesTerminees' => $listSortiesTerminees,
                'nbPages' => $nbPages,
                'page' => $page
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function statistiquesMembres(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $modeleStatistiques = $statistiques->getStatistiquesParMembre();     
        
        return $this->render('sortie/statistiquesmembres.html.twig', 
            array(
                'modele' => $modeleStatistiques,
                'annee' => $annee,
                'mois' => $mois
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function statistiquesNombreDeSortiesMembres(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $modeleStatistiques = $statistiques->getStatistiquesParMembre();
        
        return $this->render('sortie/statistiquesnombredesortiesmembres.html.twig', 
            array(
                'modele' => $modeleStatistiques,
                'annee' => $annee,
                'mois' => $mois
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function statistiquesBateaux(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $modeleStatistiques = $statistiques->getStatistiquesParBateau();
        
        return $this->render('sortie/statistiquesbateaux.html.twig', 
            array(
                'modele' => $modeleStatistiques,
                'annee' => $annee,
                'mois' => $mois
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function statistiquesNombreDeSortiesBateaux(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $modeleStatistiques = $statistiques->getStatistiquesParBateau();
        
        return $this->render('sortie/statistiquesnombredesortiesbateaux.html.twig', 
            array(
                'modele' => $modeleStatistiques,
                'annee' => $annee,
                'mois' => $mois
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function membre(Request $request, $idmembre)
    {
        $membre = $this->userRepository
            ->find($idmembre);

        // Si $membre est null, l'id n'existe pas
        if(null == $membre) {
            throw new NotFoundHttpException("Le membre d'id ".$idmembre." n'existe pas.");
        }

        // On récupère la liste des sorties terminées
        $sorties = $this->sortieRepository
            ->getSortiesMembre($idmembre);

        return $this->render('sortie/membre.html.twig',
            array(
                'membre'    => $membre,
                'sorties'   => $sorties
                ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function bateau(Request $request, $idbateau)
    {
        $bateau = $this->bateauRepository
            ->find($idbateau);

        // Si $membre est null, l'id n'existe pas
        if(null == $bateau) {
            throw new NotFoundHttpException("Le bateau d'id ".$idbateau." n'existe pas.");
        }

        // On récupère la liste des sorties terminées
        $sorties = $this->sortieRepository
            ->getSortiesBateau($idbateau);

        return $this->render('sortie/bateau.html.twig',
            array(
                'bateau'    => $bateau,
                'sorties'   => $sorties
                ));
    }
   
    /**
    * @Security("has_role('ROLE_SORTIES')")
    */
    public function ajouter(Request $request, $nbrameurs)
    {
        // On créé un objet Sortie
        $sortie = new Sortie();

        // On met la date du jour comme date par défaut et l'heure actuelle comme heure de départ
        $sortie->setDate(new \DateTime);
        $sortie->setHdepart($this->upMinutesTime(new \DateTime,5));

        // On génère le formulaire
        $form = $this->createForm(SortieAddType::class, $sortie, array('nbrameurs' => $nbrameurs));

        // Si la requête est en POST, c'est qu'on veut enregistrer la sortie
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                $this->EnregistreSortie($sortie);

                $this->addFlash('success', 'Sortie bien enregistrée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        // Sinon on affiche le formulaire pour ajouter une sortie
        return $this->render('sortie/ajouter.html.twig', array('form' => $form->createView()));
    }
    
    /**
    * @Security("has_role('ROLE_SORTIES')")
    */
    public function terminer(Request $request, $id)
    {
        $sortie = $this->getSortieById($id);

        // Si sortie est null, l'id n'existe pas
        if(null == $sortie) {
            throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

        // On initialise l'heure de retour à maintenant pour aider à la saisie
        $sortie->setHretour($this->upMinutesTime(new \DateTime, 5));       

        // On génère le formulaire
        $form = $this->createForm(SortieEndType::class, $sortie);

        // Si la requête est en POST, c'est qu'on veut enregistrer la sortie
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                $this->EnregistreSortie($sortie);

                $this->addFlash('success', 'Sortie bien enregistrée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        return $this->render('sortie/terminer.html.twig',
                                array(
                                    'form'      => $form->createView(),
                                    'sortie'    => $sortie
                                    ));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function view($id)
    {
        $sortie = $this->getSortieById($id);

        if(null == $sortie) {
            throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

        return $this->render('sortie/view.html.twig', array('sortie' => $sortie));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */    
    public function modifier(Request $request, $id)
    {
        // On récupère l'entité du sortie correspondant à l'id $id
        $sortie = $this->getSortieById($id);

        // On génère le formulaire
        $form = $this->createForm(SortieType::class, $sortie);

        // Si la requête est en POST, c'est qu'on veut enregistrer le sortie
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $sortie en base de données
                $this->EnregistreSortie($sortie);

                $this->addFlash('success', 'Sortie bien modifiée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }
        return $this->render('sortie/modifier.html.twig',
                                array('form' => $form->createView(), 'sortie' => $sortie));
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function supprimer(Request $request, $id)
    {
        // On récupère l'entité du sortie correspondant à l'id $id
        $sortie = $this->getSortieById($id);

        $sortie->setDatesupp(new \DateTime("now"));

        // On enregistre l'objet $sortie en base de données
        $this->EnregistreSortie($sortie);

        $this->addFlash('success', 'Sortie bien supprimée.');

        // On redirige vers le cahier de sortie
        return $this->redirectToRoute('aviron_sortie_home');
    }

    private function getSortieById($id)
    {
        return $this->sortieRepository
            ->find($id);
    }

    private function EnregistreSortie($sortie)
    {
        $this->entityManager->persist($sortie);
        $this->entityManager->flush();
    }

    private function upMinutesTime($datetime, $interval)
    {
        // On remet à zéro le nombre de secondes
        $second = $datetime->format("s");
        if($second > 0)
            $datetime->add(new \DateInterval("PT".(60-$second)."S"));

        // On calcul le modulo pour connaître le nombre de minutes à ajouter
        $minute = $datetime->format("i");
        $minute = $minute % $interval;

        // Si nécessaire, on ajoute le nombre de minutes pour aller à l'interval supérieur
        if($minute != 0)
        {
            // Count difference
            $diff = $interval - $minute;
            // Add difference
            $datetime->add(new \DateInterval("PT".$diff."M"));
        }

        return $datetime;
    }
}