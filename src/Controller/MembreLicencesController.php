<?php

namespace App\Controller;

use App\Entity\MembreContacts;
use App\Entity\MembreLicences;
use App\Entity\User;
use App\Form\MembreLicenceType;
use App\Form\NumeroLicenceType;
use App\Form\PreinscriptionFlow;
use App\Repository\MembreLicencesRepository;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreLicencesController extends AbstractController
{
    private $membreLicencesRepository;
    private $saisonRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, MembreLicencesRepository $membreLicencesRepository, SaisonRepository $saisonRepository)
    {
        $this->membreLicencesRepository = $membreLicencesRepository;
        $this->saisonRepository = $saisonRepository;
        $this->entityManager = $entityManager;
    }

    public function preinscription(PreinscriptionFlow $flow, UserPasswordEncoderInterface $encoder, SessionInterface $session)
    {
        $membre = new User();
        $contactPortable = new MembreContacts();
        $contactPortable->setTypeContact(1);
        $membre->addContact($contactPortable);

        $contactUrgence = new MembreContacts();
        $contactUrgence->setTypeContact(2);
        $membre->addContact($contactUrgence);

        $licence = new MembreLicences();
        $membre->addLicence($licence);

        $flow->bind($membre);

        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                $form = $flow->createForm();
            } else {
                $newEncodedPassword = $encoder->encodePassword($membre, $membre->getPassword());
                $membre->setPassword($newEncodedPassword);
                $this->EnregistreMembre($membre);

                $session->set('idFicheInscription', $licence->getId());

                $flow->reset();

                return $this->render('membre_licences/preinscriptionenvoyee.html.twig');
            }
        }

        return $this->render('membre_licences/preinscription.html.twig', [
            'form' => $form->createView(),
            'flow' => $flow,
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function preinscriptions()
    {
        $preinscriptions = $this->membreLicencesRepository->DonnePreinscriptions();
        $licencesASaisir = $this->membreLicencesRepository->DonneLicencesASaisir();

        return $this->render(
            'membre_licences/preinscriptions.html.twig',
            array(
                'preinscriptions'   => $preinscriptions,
                'licencesasaisir'   => $licencesASaisir
            )
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function validerInscription(Request $request, $id)
    {
        $licence = $this->DonneLicence($id);
        $licence->setSaison($this->saisonRepository->DonneDerniereSaison());

        $form = $this->createForm(MembreLicenceType::class, $licence);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $licence->setDateValidationInscription(new \DateTime("now"));
                $licence->setUserValidationInscription($this->getUser());
                $this->EnregistreLicence($licence);

                $this->addFlash('success', 'Inscription validée : licence à saisir.');

                return $this->redirectToRoute('aviron_membre_licences_preinscriptions');
            }
        }

        return $this->render(
            'membre_licences/validerinscription.html.twig',
            array('form' => $form->createView(), 'demande' => $licence)
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function supprimerInscription($id)
    {
        $licence = $this->DonneLicence($id);
        $licence->setDateSuppressionInscription(new \DateTime("now"));
        $licence->setUserSuppressionInscription($this->getUser());
        $this->EnregistreLicence($licence);

        $this->addFlash('success', 'Demande d\'inscription bien supprimée.');

        return $this->redirectToRoute('aviron_membre_licences_preinscriptions');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function marquerCommeSaisie(Request $request, $id)
    {
        $licence = $this->DonneLicence($id);
        $membre = $licence->getMembre();

        if ($membre->getLicence() == null) {
            $form = $this->createForm(NumeroLicenceType::class, $membre);

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $this->EnregistreMembre($membre);

                    $licence->setDateSaisieLicence(new \DateTime("now"));
                    $licence->setUserSaisieLicence($this->getUser());
                    $this->EnregistreLicence($licence);

                    $this->addFlash('success', 'Licence marquée comme étant saisie.');

                    return $this->redirectToRoute('aviron_membre_licences_preinscriptions');
                }
            }

            return $this->render(
                'membre_licences/validerinscription.html.twig',
                array('form' => $form->createView(), 'demande' => $licence)
            );
        } else {
            $licence->setDateSaisieLicence(new \DateTime("now"));
            $licence->setUserSaisieLicence($this->getUser());
            $this->EnregistreLicence($licence);

            $this->addFlash('success', 'Licence marquée comme étant saisie.');

            return $this->redirectToRoute('aviron_membre_licences_preinscriptions');
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function voirFicheInscription($id)
    {
        return $this->DonneFicheInscription($id);
    }

    public function telechargerFicheInscription(SessionInterface $session)
    {
        $id = $session->get('idFicheInscription');
        return $this->DonneFicheInscription($id);
    }

    private function DonneFicheInscription($id)
    {
        $licence = $this->DonneLicence($id);
        $membre = $licence->getMembre();

        $ficheInscription = new TemplateProcessor('Fiche_inscription_test.docx');
        $ficheInscription->setValue('nom', $membre->getNom());
        $ficheInscription->saveAs('plop.docx');

        $reponse = new BinaryFileResponse('plop.docx');
        $reponse->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'plop.docx'
        );
        return $reponse;
    }

    private function DonneLicence($id)
    {
        $licence = $this->membreLicencesRepository->find($id);

        if (null == $licence) {
            throw new NotFoundHttpException("La licence d'id " . $id . " n'existe pas.");
        }

        return $licence;
    }

    private function EnregistreLicence($licence)
    {
        $this->entityManager->persist($licence);
        $this->entityManager->flush();
    }

    private function EnregistreMembre($membre)
    {
        $this->entityManager->persist($membre);
        $this->entityManager->flush();
    }
}
