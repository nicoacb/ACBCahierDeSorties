<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Form\SortieAddType;
use App\Form\SortieEndType;
use App\Statistiques\StatistiquesSorties;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SortieController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function accueil()
    {
        return $this->render('sortie/accueil.html.twig');
    }

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
            $listsorties = $this->getDoctrine()
    	        ->getManager()
    	        ->getRepository('App:Sortie')
                ->getSortiesEnCours();
        }
        
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->getDoctrine()
    	    ->getManager()
    	    ->getRepository('App:Sortie')
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
        // On récupère l'entité du membre correspondante à l'id $idmembre
        $membre = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:User')
            ->find($idmembre);

        // Si $membre est null, l'id n'existe pas
        if(null == $membre) {
            throw new NotFoundHttpException("Le membre d'id ".$idmembre." n'existe pas.");
        }

        // On récupère la liste des sorties terminées
        $sorties = $this->getDoctrine()
    	    ->getManager()
    	    ->getRepository('App:Sortie')
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
        // On récupère l'entité du membre correspondante à l'id $idmembre
        $bateau = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Bateau')
            ->find($idbateau);

        // Si $membre est null, l'id n'existe pas
        if(null == $bateau) {
            throw new NotFoundHttpException("Le bateau d'id ".$idbateau." n'existe pas.");
        }

        // On récupère la liste des sorties terminées
        $sorties = $this->getDoctrine()
    	    ->getManager()
    	    ->getRepository('App:Sortie')
            ->getSortiesBateau($idbateau);

        return $this->render('sortie/bateau.html.twig',
            array(
                'bateau'    => $bateau,
                'sorties'   => $sorties
                ));
    }
   
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
                // On enregistre l'objet $sortie en base de données
                $em = $this->GetDoctrine()->getManager();
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'Sortie bien enregistrée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        // Sinon on affiche le formulaire pour ajouter une sortie
        return $this->render('sortie/ajouter.html.twig', array('form' => $form->createView()));
    }
    
    public function terminer(Request $request, $id)
    {
        // On récupère l'entité de la sortie correspondante à l'id $id
        $sortie = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie')
            ->find($id);

        // On initialise l'heure de retour à maintenant pour aider à la saisie
        $sortie->setHretour($this->upMinutesTime(new \DateTime, 5));

        // Si sortie est null, l'id n'existe pas
        if(null == $sortie) {
            throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

        // On génère le formulaire
        $form = $this->createForm(SortieEndType::class, $sortie);

        // Si la requête est en POST, c'est qu'on veut enregistrer la sortie
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $sortie en base de données
                $em = $this->GetDoctrine()->getManager();
                $em->persist($sortie);
                $em->flush();

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
    
    public function view($id)
    {
        // On récupère le repository
        $reposity = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        // On récupère l'entité de la sortie correspondante à l'id $id
        $sortie = $reposity->find($id);

        // Si sortie est null, l'id n'existe pas
        if(null == $sortie) {
            throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

        // On renvoie la vue en réponse
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
                $this->persistSortie($sortie);

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
        $this->persistSortie($sortie);

        $this->addFlash('success', 'Sortie bien supprimée.');

        // On redirige vers le cahier de sortie
        return $this->redirectToRoute('aviron_sortie_home');
    }

    private function getSortieById($id)
    {
        return $this->getDoctrine()
        ->getManager()
        ->getRepository('App:Sortie')
        ->find($id);
    }

    private function persistSortie($sortie)
    {
        // On enregistre l'objet $sortie en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->persist($sortie);
        $em->flush();
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