<?php

namespace App\Controller;

use App\Form\MotDePasseOublieType;
use App\Form\MotDePasseType;
use App\Form\NouveauMotDePasseType;
use App\Form\ReinscriptionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, 
        UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();

            if (in_array('ROLE_SORTIES', $roles, true)) {
                return $this->redirectToRoute('aviron_sortie_home');
            } else {
                return $this->redirectToRoute('aviron_sortie_home');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('authentification/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout()
    {
        return $this->redirectToRoute('authentification_logout');
    }

    public function changerMotDePasse(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(MotDePasseType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ancienMotDePasse = $form->get('ancienMotDePasse')->getData();

            if ($encoder->isPasswordValid($user, $ancienMotDePasse)) {
                $newEncodedPassword = $encoder->encodePassword($user, $form->get('nouveauMotDePasse')->getData());
                $user->setPassword($newEncodedPassword);

                $this->EnregistreMembre($user);

                $this->addFlash('success', 'Votre mot de passe a bien été changé !');

                return $this->redirectToRoute('aviron_accueil');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
        $this->getDoctrine()->getManager()->refresh($user);

        return $this->render('authentification/changermotdepasse.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function motDePasseOublie(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(MotDePasseOublieType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $login = $form->get('login')->getData();
            $membre = $this->DonneMembreParUsername($login);

            if (null != $membre) {
                $token = $this->GenereToken();
                $membre->setConfirmationToken($token);
                $this->EnregistreMembre($membre);

                $email = (new TemplatedEmail())
                    ->from(new Address('nepasrepondre@avironclub.fr', 'Aviron Club de Bourges'))
                    ->to($membre->getEmail())
                    ->subject('Nouveau mot de passe pour votre compte Aviron Club de Bourges')
                    ->htmlTemplate('authentification/emailmotdepasseoublie.html.twig')
                    ->context([
                        'membre' => $membre, 'token' => $token
                    ]);

                $mailer->send($email);
            }

            $this->addFlash('success', 'Un lien pour définir votre nouveau mot de passe a été envoyé à l\'adresse email que vous nous avez fournie lors de votre inscription.');
        }

        return $this->render('authentification/motdepasseoublie.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function nouveauMotDePasse(Request $request, UserPasswordEncoderInterface $encoder, $token): Response
    {
        $membre = $this->DonneMembreParToken($token);

        if (null == $membre) {
            throw new NotFoundHttpException("Ce lien a déjà été utilisé.");
        }

        $form = $this->createForm(NouveauMotDePasseType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newEncodedPassword = $encoder->encodePassword($membre, $form->get('nouveauMotDePasse')->getData());
            $membre->setPassword($newEncodedPassword);
            $membre->setConfirmationToken(null);

            $this->EnregistreMembre($membre);

            $this->addFlash('success', 'Votre mot de passe a bien été changé !');

            $token = new UsernamePasswordToken($membre, null, 'main', $membre->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('aviron_accueil');
        }

        return $this->render('authentification/nouveaumotdepasse.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function envoyerLienReinscriptionParMail(Request $request, MailerInterface $mailer, $idmembre)
    {
        $membre = $this->DonneMembreParId($idmembre);

        $token = $this->GenereToken();
        $membre->setReinscriptionToken($token);
        $this->EnregistreMembre($membre);

        $email = (new TemplatedEmail())
            ->from(new Address('nepasrepondre@avironclub.fr', 'Aviron Club de Bourges'))
            ->to($membre->getEmail())
            ->subject('Nouvelle saison - votre lien pour vour réinscrire')
            ->htmlTemplate('authentification/emailenvoyerlienreinscription.html.twig')
            ->context([
                'membre' => $membre
            ]);

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $mailer->send($email);

        $this->addFlash('success', 'Mail envoyé à ' . $membre->getPrenomNom() . ' (' . $membre->getEmail() . ').');

        // On redirige vers la liste des membres
        return $this->redirectToRoute('aviron_users_liste');
    }

    public function reinscriptionToken(Request $request, $token)
    {
        $membre = $this->DonneMembreParReinscriptionToken($token);

        if (null == $membre) {
            throw new NotFoundHttpException("Ce lien a déjà été utilisé.");
        }

        $form = $this->createForm(ReinscriptionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if(trim(strtoupper($form->get('email')->getData())) == trim(strtoupper($membre->getEmail())))
            {
                $sessionToken = new UsernamePasswordToken($membre, null, 'main', $membre->getRoles());
                $this->get('security.token_storage')->setToken($sessionToken);

                return $this->redirectToRoute('aviron_membre_licences_reinscription');
            }
            else
            {
                $this->addFlash('danger', 'L\'email saisi ne correpond pas à celui de votre compte !');
            }
        }

        return $this->render('authentification/reinscription.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function DonneMembreParId($id)
    {
        $membre = $this->userRepository->find($id);

        if (null == $membre) {
            throw new NotFoundHttpException("Le membre d'id " . $id . " n'existe pas.");
        }

        return $membre;
    }

    private function DonneMembreParUsername($username)
    {
        return $this->userRepository->findOneByUsername($username);
    }

    private function DonneMembreParToken($token)
    {
        return $this->userRepository->findOneByConfirmationToken($token);
    }

    private function DonneMembreParReinscriptionToken($reinscriptionToken)
    {
        return $this->userRepository->findOneByReinscriptionToken($reinscriptionToken);
    }

    private function EnregistreMembre($membre)
    {
        $this->entityManager->persist($membre);
        $this->entityManager->flush();
    }

    private function GenereToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}