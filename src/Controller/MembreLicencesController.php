<?php

namespace App\Controller;

use App\Entity\MembreContacts;
use App\Entity\MembreLicences;
use App\Entity\User;
use App\Form\MembreLicenceType;
use App\Form\NumeroLicenceType;
use App\Form\PreinscriptionFlow;
use App\Form\ReinscriptionFlow;
use App\Repository\MembreLicencesRepository;
use App\Repository\SaisonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreLicencesController extends AbstractController
{
    private $membreLicencesRepository;
    private $saisonRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,
        MembreLicencesRepository $membreLicencesRepository,
        SaisonRepository $saisonRepository)
    {
        $this->membreLicencesRepository = $membreLicencesRepository;
        $this->saisonRepository = $saisonRepository;
        $this->entityManager = $entityManager;
    }

    public function preinscription(PreinscriptionFlow $flow, UserPasswordEncoderInterface $encoder, SessionInterface $session, MailerInterface $mailer)
    {
        $membre = new User();
        $contactPortable = new MembreContacts();
        $contactPortable->setTypeContact(1);
        $membre->addContact($contactPortable);

        $contactUrgence = new MembreContacts();
        $contactUrgence->setTypeContact(2);
        $membre->addContact($contactUrgence);

        $licence = new MembreLicences();
        $licence->setDateDemandeInscription(new \DateTime("now"));
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

                $this->EnvoyerMailInscription($mailer, $membre);

                return $this->render(
                    'membre_licences/preinscriptionenvoyee.html.twig',
                    array(
                        'reinscription'   => false
                    )
                );
            }
        }

        return $this->render('membre_licences/preinscription.html.twig', [
            'form' => $form->createView(),
            'flow' => $flow,
        ]);
    }

    public function reinscription(ReinscriptionFlow $flow, UserPasswordEncoderInterface $encoder, SessionInterface $session, MailerInterface $mailer)
    {
        $membre = $this->getUser();

        if ($membre->getContacts()->Count() == 0) {
            $contactPortable = new MembreContacts();
            $contactPortable->setTypeContact(1);
            $membre->addContact($contactPortable);

            $contactUrgence = new MembreContacts();
            $contactUrgence->setTypeContact(2);
            $membre->addContact($contactUrgence);
        }

        $licence = new MembreLicences();
        $licence->setDateDemandeInscription(new \DateTime("now"));
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

                $this->EnvoyerMailInscription($mailer, $membre);

                return $this->render(
                    'membre_licences/preinscriptionenvoyee.html.twig',
                    array(
                        'reinscription'   => true
                    )
                );
            }
        }

        return $this->render('membre_licences/reinscription.html.twig', [
            'form' => $form->createView(),
            'flow' => $flow,
        ]);
    }

    public function inscriptionRecue()
    {
        $membre = $this->getUser();

        return $this->render('membre_licences/inscriptionrecue.html.twig', [
            'membre' => $membre
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function preinscriptions()
    {
        $preinscriptions = $this->membreLicencesRepository->DonnePreinscriptions();
        $licencesASaisir = $this->membreLicencesRepository->DonneLicencesASaisir();
        $fichesAImprimer = $this->membreLicencesRepository->DonneFicheInscriptionAImprimer();

        return $this->render(
            'membre_licences/preinscriptions.html.twig',
            array(
                'preinscriptions'   => $preinscriptions,
                'licencesasaisir'   => $licencesASaisir,
                'fichesaimprimer'   => $fichesAImprimer
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

                $membre = $licence->getMembre();
                $membre->addRole('ROLE_USER');

                $this->EnregistreMembre($membre);
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
    public function marquerCommeImprimee($id)
    {
        $licence = $this->DonneLicence($id);
        $licence->setDateImpressionFiche(new \DateTime("now"));
        $this->EnregistreLicence($licence);

        $this->addFlash('success', 'Fiche d\'inscription marquée comme imprimée.');

        return $this->redirectToRoute('aviron_membre_licences_preinscriptions');
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
        $ficheInscription->setValue('prenom', $membre->getPrenom());
        $ficheInscription->setValue('datedenaissance', $this->DonneDateEnChaine($membre->getDatenaissance()));
        $ficheInscription->setValue('nationalite', $membre->getNationalite()->getNationalite());
        $ficheInscription->setValue('adresse1', strval($membre->getNumeroVoie()) . ' ' . $membre->getTypeVoie() . ' ' . $membre->getLibelleVoie() . ' ' . $membre->getLieuDit());
        $ficheInscription->setValue('adresse2', $membre->getImmBatRes() . ' ' . $membre->getAptEtageEsc());
        $ficheInscription->setValue('ville', $membre->getVille());
        $ficheInscription->setValue('codepostal', $membre->getCodePostal());
        $contacts = [];
        foreach ($membre->getContacts() as $contact) {
            array_push($contacts, ['contacts' => $this->DonneTypeContact($contact->getTypeContact()), 'nomcontact' => $contact->getNomContact(), 'telephoneEmail' => $contact->getTelephoneEmail()]);
        }
        $ficheInscription->cloneRowAndSetValues('contacts', $contacts);
        $ficheInscription->setValue('email', $membre->getEmail());
        $ficheInscription->setValue('dateinscription', $this->DonneDateEnChaine($licence->getDateDemandeInscription()));
        $ficheInscription->setValue('datesaisie', $this->DonneDateEnChaine($licence->getDateSaisieLicence()));
        $ficheInscription->setValue('licence', $membre->getLicence());
        $ficheInscription->setValue('enviespratique', $this->DonneEnviesPratique($membre->getEnviesPratiques()));
        if($membre->getFrequencePratique() != null)
        {
            $ficheInscription->setValue('frequencepratique', $membre->getFrequencePratique()->getFrequence());    
        }
        else
        {
            $ficheInscription->setValue('frequencepratique', '');
        }
        $ficheInscription->setValue('permis', $this->DonnePermis($membre));
        $ficheInscription->setValue('secourisme', $membre->getEngagementAssociation()->getBrevetSecourisme());
        $ficheInscription->setValue('encadrement', $membre->getEngagementAssociation()->getEncadrementSportif());
        $ficheInscription->setValue('communication', $membre->getEngagementAssociation()->getCommunication());
        $ficheInscription->setValue('informatique', $membre->getEngagementAssociation()->getInformatique());
        $ficheInscription->setValue('technique', $membre->getEngagementAssociation()->getTechnique());
        $ficheInscription->setValue('autres', $membre->getEngagementAssociation()->getAutres());
        
        $ficheInscription->saveAs($membre->getPrenomNom() . '.docx');

        $reponse = new BinaryFileResponse($membre->getPrenomNom() . '.docx');
        $reponse->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $membre->getPrenomNom() . '.docx'
        );
        return $reponse;
    }

    private function DonneDateEnChaine($date)
    {
        if ($date != null)
            return $date->format('d/m/Y');
        else
            return '';
    }

    private function DonneEnviesPratique($enviesPratique)
    {
        $envies = '';
        foreach ($enviesPratique as $envie) {
            $envies = $envies . $envie->getEnvie() . ', ';
            /*if ($envie->getId() == 1) {
                $envies = $envies . 'prendre soin de ma santé, ';
            }
            if ($envie == 2) {
                $envies = $envies . 'pratiquer une activité sportive sur l\'eau, ';
            }
            if ($envie == 3) {
                $envies = $envies . 'faire de l\'AviFit, ';
            }
            if ($envie == 4) {
                $envies = $envies . 'faire de la compétition, ';
            }
            if ($envie == 5) {
                $envies = $envies . 'avoir une pratique collective, ';
            }
            if ($envie == 6) {
                $envies = $envies . 'avoir une pratique individuelle, ';
            }
            if ($envie == 7) {
                $envies = $envies . 'faire de la randonnée, ';
            }*/
        }
        return $envies;
    }

    private function DonnePermis($membre)
    {
        $permis = '';
        if ($membre->getEngagementAssociation()->getAPermisBateauEauxInterieures()) {
            $permis = $permis . ' bateaux eaux intérieures,';
        }
        if ($membre->getEngagementAssociation()->getAPermisBateauCotier()) {
            $permis = $permis . ' bateaux côtier,';
        }
        if ($membre->getEngagementAssociation()->getAPermisRemorqueEB()) {
            $permis = $permis . ' remorque EB,';
        }
        if ($membre->getEngagementAssociation()->getAPermisRemorqueB96()) {
            $permis = $permis . ' remorque B96';
        }
        return $permis;
    }

    private function DonneTypeContact($typeContact)
    {
        if ($typeContact == 1) {
            return 'Numéro de portable';
        } elseif ($typeContact == 2) {
            return 'Numéro de portable de la personne à prévenir en cas d\'urgence';
        } elseif ($typeContact == 3) {
            return 'Numéro de portable de la Mère';
        } elseif ($typeContact == 4) {
            return 'Numéro de portable du Père';
        } elseif ($typeContact == 5) {
            return 'Numéro de portable du Responsable légal';
        } elseif ($typeContact == 6) {
            return 'Autre (précisez dans le champ Nom  du contact';
        } elseif ($typeContact == 7) {
            return 'Email de la Mère';
        } elseif ($typeContact == 8) {
            return 'Email du Père';
        } elseif ($typeContact == 9) {
            return 'Email du Responsable légal';
        } else {
            return '';
        }
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

    private function EnvoyerMailInscription($mailer, $membre)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('nepasrepondre@avironclub.fr', 'Aviron Club de Bourges'))
            ->to($membre->getEmail())
            ->subject('Demande d\'inscription reçue')
            ->htmlTemplate('membre_licences/emailpreinscription.html.twig')
            ->context([
                'membre' => $membre
            ]);

        $mailer->send($email);
    }
}
