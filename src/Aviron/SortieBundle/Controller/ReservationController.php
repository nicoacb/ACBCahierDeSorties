<?php

namespace Aviron\SortieBundle\Controller;

use Aviron\SortieBundle\Entity\Entrainement;
use Aviron\SortieBundle\Entity\Reservation;
use Aviron\SortieBundle\Form\EntrainementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function desinscrireAction(Request $request, $id)
    {
        $reservation = new Reservation();

        // On récupère l'entité de la sortie correspondante à l'id $id
        $reservation = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Reservation')
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
    public function participantsAction($id)
    {
        $participants =  $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Reservation')
            ->findBy([
                'identrainement' => $id,
                'datesupp' => null
            ]);

        return $this->render('AvironSortieBundle:Reservation:participants.html.twig', array('participants' => $participants));
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function supprimerEntrainementAction(Request $request, $id)
    {
        // On récupère l'entité de l'entrainement correspondante à l'id $id
        $entrainement = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironSortieBundle:Entrainement')
            ->find($id);

        // Si $entrainement est null, l'id n'existe pas
        if(null == $entrainement) {
            throw new NotFoundHttpException("L'entrainement d'id ".$id." n'existe pas.");
        }

        $entrainement->setIdutsupp($this->getUser()->getId());
        $entrainement->setDatesupp(new \DateTime());
        $this->persistEntrainement($entrainement);

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Entraînement bien supprimé.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_admin_entrainements');
    }

    private function persistEntrainement($entrainement)
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
}
