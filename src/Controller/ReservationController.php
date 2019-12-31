<?php

namespace App\Controller;

use App\Entity\Entrainement;
use App\Entity\Reservation;
use App\Form\EntrainementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationController extends Controller
{    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listerEntrainementsAVenir()
    {
        $listeEntrainementsAVenir = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Entrainement')
            ->DonneEntrainementsAVenir();

        $listeEntrainementsPasses = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Entrainement')
            ->DonneEntrainementsPasses();

        $reservations = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Reservation')
            ->DonneReservations($this->getUser()->getId());

        $estInscrit = array();
        $nbreservations = array();
        foreach ($listeEntrainementsAVenir as $entrainement) {
            foreach ($reservations as $reservation) {
                if ($entrainement->getId() == $reservation->getIdentrainement()) {
                    array_push($estInscrit, $entrainement->getId());
                }
            }

            $nbreservations[$entrainement->getId()] = $this->DonneNombreDeReservations($entrainement->getId());
        }

        foreach ($listeEntrainementsPasses as $entrainement) {
            $nbreservations[$entrainement->getId()] = $this->DonneNombreDeReservations($entrainement->getId());
        }

        return $this->render('reservation/lister.html.twig', array(
            'listeEntrainementsAVenir' => $listeEntrainementsAVenir,
            'listeEntrainementsPasses' => $listeEntrainementsPasses,
            'reservations' => $estInscrit,
            'nbreservations' => $nbreservations
        ));
    }

    private function DonneNombreDeReservations($identrainement)
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Reservation')
            ->CompteNombreDeReservations($identrainement);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function reserver(Request $request, $id)
    {
        $reservation = new Reservation();
        $reservation->setIdentrainement($id);
        $reservation->setDatereservation(new \DateTime());
        $reservation->setIdut($this->getUser());

        $this->persistReservation($reservation);

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Inscription validée.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function desinscrire(Request $request, $id)
    {
        $reservation = new Reservation();

        // On récupère l'entité de la sortie correspondante à l'id $id
        $reservation = $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Reservation')
            ->findOneBy([
                'identrainement' => $id,
                'idut' => $this->getUser()->getId(),
                'datesupp' => null
            ]);

        // Si sortie est null, l'id n'existe pas
        if (null == $reservation) {
            throw new NotFoundHttpException("La sortie d'id " . $id . " n'existe pas.");
        }

        $reservation->setIdutsupp($this->getUser()->getId());
        $reservation->setDatesupp(new \DateTime());

        $this->persistReservation($reservation);

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Désinscription validée.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function participants($id)
    {
        $participants =  $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Reservation')
            ->findBy(
                [
                    'identrainement' => $id,
                    'datesupp' => null
                ],
                [
                    'datereservation' => 'ASC'
                ]
            );

        return $this->render('reservation/participants.html.twig', array('participants' => $participants));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function ajouterEntrainement(Request $request)
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
                $this->SauvegardeEntrainement($entrainement);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Entraînement bien enregistré.');

                // On redirige vers la liste des entraînements
                return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
            }
        }
        return $this->render('reservation/ajouter.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function supprimerEntrainement(Request $request, $id)
    {
        $entrainement = $this->DonneEntrainement($id);

        // Si $entrainement est null, l'id n'existe pas
        if (null == $entrainement) {
            throw new NotFoundHttpException("L'entrainement d'id " . $id . " n'existe pas.");
        }

        $entrainement->setIdutsupp($this->getUser()->getId());
        $entrainement->setDatesupp(new \DateTime());
        $this->SauvegardeEntrainement($entrainement);

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Entraînement bien supprimé.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function modifierEntrainement(Request $request, $id)
    {
        $entrainement = $this->DonneEntrainement($id);

        // On génère le formulaire
        $form = $this->createForm(EntrainementType::class, $entrainement);

        // Si la requête est en POST, c'est qu'on veut enregistrer l'entrainement
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->SauvegardeEntrainement($entrainement);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Entraînement bien enregistré.');

                return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
            }
        }
        return $this->render(
            'reservation/modifier.html.twig',
            array('form' => $form->createView())
        );
    }

    private function SauvegardeEntrainement($entrainement)
    {
        // On enregistre l'objet $entrainement en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->persist($entrainement);
        $em->flush();
    }

    private function persistReservation($reservation)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();
    }

    private function DonneEntrainement($id)
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Entrainement')
            ->find($id);
    }
}
