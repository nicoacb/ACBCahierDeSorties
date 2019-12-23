<?php

namespace Aviron\SortieBundle\Controller;

use Aviron\SortieBundle\Entity\Entrainement;
use Aviron\SortieBundle\Entity\Reservation;
use Aviron\SortieBundle\Form\EntrainementType;
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
            ->getRepository('AvironSortieBundle:Entrainement')
            ->getListeEntrainements();

        return $this->render('AvironSortieBundle:Reservation:index.html.twig', array('listeEntrainements' => $listeEntrainements));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listerEntrainementsAVenirAction()
    {
        $listeEntrainements = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Entrainement')
            ->getListeEntrainementsAVenir();

        $reservations = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Reservation')
            ->DonneReservations($this->getUser()->getId());

        $estInscrit = array();
        foreach ($listeEntrainements as $entrainement) {
            foreach ($reservations as $reservation) {
                if ($entrainement->getId() == $reservation->getIdentrainement()) {
                    array_push($estInscrit, $entrainement->getId());
                }
            }
        }

        return $this->render('AvironSortieBundle:Reservation:lister.html.twig', array('listeEntrainements' => $listeEntrainements, 'reservations' => $estInscrit));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function reserverAction(Request $request, $id)
    {
        $reservation = new Reservation();
        $reservation->setIdentrainement($id);
        $reservation->setDatereservation(new \DateTime());
        $reservation->setIdut($this->getUser()->getId());

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Inscription validée.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function ajouterEntrainementAction(Request $request)
    {
        // On créé un objet Reservation
        $entrainement = new Entrainement();

        // On génère le formulaire
        $form = $this->createForm(EntrainementType::class, $entrainement);

        // Si la requête est en POST, c'est qu'on veut enregistrer l'entraînement
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $bateau en base de données
                $this->persistEntrainement($entrainement);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Entraînement bien enregistré.');

                // On redirige vers la liste des entraînements
                return $this->redirectToRoute('aviron_sortie_reservation_admin_entrainements');
            }
        }
        return $this->render('AvironSortieBundle:Reservation:ajouter.html.twig', array('form' => $form->createView()));
    }

    private function persistEntrainement($entrainement)
    {
        // On enregistre l'objet $entrainement en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->persist($entrainement);
        $em->flush();
    }
}