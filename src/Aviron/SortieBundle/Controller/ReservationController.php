<?php

namespace Aviron\SortieBundle\Controller;

use Aviron\SortieBundle\Entity\Reservation;
use Aviron\SortieBundle\Form\ReservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function listerEntrainementsAction()
    {
        $listeEntrainements = $this->getDoctrine()
    	->getManager()
    	->getRepository('AvironSortieBundle:Reservation')
        ->getListeEntrainements();

        return $this->render('AvironSortieBundle:Reservation:index.html.twig', array('listeEntrainements' => $listeEntrainements));
    }

    
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function ajouterEntrainementAction(Request $request)
    {
        // On créé un objet Reservation
        $entrainement = new Reservation();

        // On génère le formulaire
        $form = $this->createForm(ReservationType::class, $entrainement);

        // Si la requête est en POST, c'est qu'on veut enregistrer l'entraînement
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $bateau en base de données
                $this->persistEntrainement($entrainement);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Entraînement bien enregistré.');

                // On redirige vers la liste des entraînements
                return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
            }
        }
        return $this->render('AvironSortieBundle:Reservation:ajouter.html.twig', array('form' => $form->createView()));
    }

    private function persistEntrainement($entrainement)
    {
        // On enregistre l'objet $bateau en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->persist($entrainement);
        $em->flush();
    }
}
