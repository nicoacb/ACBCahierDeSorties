<?php

namespace App\Controller;

use App\Entity\Entrainement;
use App\Entity\Reservation;
use App\Form\EntrainementType;
use App\Form\ReservationType;
use App\Repository\EntrainementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationController extends AbstractController
{
    private $entityManager;
    private $entrainementRepository;
    private $reservationRepository;

    public function __construct(EntityManagerInterface $entityManager,
        EntrainementRepository $entrainementRepository,
        ReservationRepository $reservationRepository)
    {
        $this->entityManager = $entityManager;
        $this->entrainementRepository = $entrainementRepository;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listerEntrainementsAVenir()
    {
        $listeEntrainementsAVenir = $this->entrainementRepository
            ->DonneEntrainementsAVenir();

        $listeEntrainementsPasses = $this->entrainementRepository
            ->DonneEntrainementsPasses();

        $reservations = $this->reservationRepository
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
        return $this->reservationRepository
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

        $this->addFlash('success', 'Inscription validée.');

        // On redirige vers la liste des entrainements
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function reserverPourUnMembre(Request $request, $identrainement)
    {
        $reservation = new Reservation();
        $reservation->setIdentrainement($identrainement);
        $reservation->setDatereservation(new \DateTime());

        $form = $this->createForm(ReservationType::class, $reservation);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->persistReservation($reservation);
                $this->addFlash('success', 'Inscription validée.');
                return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
            }
        }
        return $this->render('reservation/reserver.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function desinscrire(Request $request, $id)
    {
        $this->desinscrireUnMembreDUnEntrainement($request, $id, $this->getUser()->getId());
        
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function desinscrireUnMembre(Request $request, $identrainement, $idmembre)
    {
        $this->desinscrireUnMembreDUnEntrainement($request, $identrainement, $idmembre);
        
        return $this->redirectToRoute('aviron_sortie_reservation_entrainements');
    }

    private function desinscrireUnMembreDUnEntrainement(Request $request, $identrainement, $idmembre)
    {
        $reservation = new Reservation();

        // On récupère l'entité de la sortie correspondante à l'id $id
        $reservation = $this->reservationRepository
            ->findOneBy([
                'identrainement' => $identrainement,
                'idut' => $idmembre,
                'datesupp' => null
            ]);

        if (null == $reservation) {
            throw new NotFoundHttpException("La réservation d'id " . $identrainement . " n'existe pas.");
        }

        $reservation->setIdutsupp($this->getUser()->getId());
        $reservation->setDatesupp(new \DateTime());

        $this->persistReservation($reservation);

        $this->addFlash('success', 'Désinscription validée.');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function participants($id)
    {
        $participants =  $this->reservationRepository
            ->findBy(
                [
                    'identrainement' => $id,
                    'datesupp' => null
                ],
                [
                    'datereservation' => 'ASC'
                ]
            );

        return $this->render('reservation/participants.html.twig', array('identrainement' => $id, 'participants' => $participants));
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

                $this->addFlash('success', 'Entraînement bien enregistré.');

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

        $this->addFlash('success', 'Entraînement bien supprimé.');

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

                $this->addFlash('success', 'Entraînement bien enregistré.');

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
        $this->entityManager->persist($entrainement);
        $this->entityManager->flush();
    }

    private function persistReservation($reservation)
    {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    private function DonneEntrainement($id)
    {
        return $this->entrainementRepository
            ->find($id);
    }
}
