<?php

namespace Aviron\SortieBundle\Controller;

use Aviron\SortieBundle\Entity\Sortie;
use Aviron\SortieBundle\Form\SortieType;
use Aviron\SortieBundle\Form\SortieAddType;
use Aviron\SortieBundle\Form\SortieEndType;
use Aviron\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SortieController extends Controller
{
    public function indexAction($page)
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
    	        ->getRepository('AvironSortieBundle:Sortie')
                ->findByHretour(NULL);
        }
        
        // On récupère la liste des sorties terminées
        $listSortiesTerminees = $this->getDoctrine()
    	    ->getManager()
    	    ->getRepository('AvironSortieBundle:Sortie')
    	    ->getSortiesTerminees($page, $nbParPage);

        // On calcule le nombre total de pages grâces au count($listSortiesTerminees) qui retourne 
        // le nombre total de sorties terminées
        $nbPages = ceil(count($listSortiesTerminees) / $nbParPage);
		 
        // Si la page n'existe pas, on lève une erreur 404
        if($page != 1 && $page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('AvironSortieBundle:Sortie:index.html.twig', 
            array(
                'listSorties' => $listsorties,
                'listSortiesTerminees' => $listSortiesTerminees,
                'nbPages' => $nbPages,
                'page' => $page
            ));
    }
    
    public function ajouterAction(Request $request, $nbrameurs)
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

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Sortie bien enregistrée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        // Sinon on affiche le formulaire pour ajouter une sortie
        return $this->render('AvironSortieBundle:Sortie:ajouter.html.twig', array('form' => $form->createView()));
    }
    
    public function terminerAction(Request $request, $id)
    {
        // On récupère l'entité de la sortie correspondante à l'id $id
        $sortie = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Sortie')
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

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Sortie bien enregistrée.');

                // On redirige vers le cahier de sortie
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        return $this->render('AvironSortieBundle:Sortie:terminer.html.twig',
                                array(
                                    'form'      => $form->createView(),
                                    'sortie'    => $sortie
                                    ));
    }
    
    public function viewAction($id)
    {
        // On récupère le repository
        $reposity = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Sortie');

        // On récupère l'entité de la sortie correspondante à l'id $id
        $sortie = $reposity->find($id);

        // Si sortie est null, l'id n'existe pas
        if(null == $sortie) {
            throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

        // On renvoie la vue en réponse
        return $this->render('AvironSortieBundle:Sortie:view.html.twig', array('sortie' => $sortie));
        
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */    
    public function editAction($id)
    {
        return $this->render('AvironSortieBundle:Sortie:edit.html.twig');
    }
    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function supprimerAction(Request $request, $id)
    {
        // On récupère l'entité de la sortie correspondante à l'id $id
        $sortie = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Sortie')
            ->find($id);

        // On enregistre l'objet $sortie en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->remove($sortie);
        $em->flush();

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Sortie bien supprimée.');

        // On redirige vers le cahier de sortie
        return $this->redirectToRoute('aviron_sortie_home');
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