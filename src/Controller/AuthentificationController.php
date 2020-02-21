<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MotDePasseOublieType;
use App\Form\MotDePasseType;
use App\Form\NouveauMotDePasseType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('aviron_accueil');
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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(MotDePasseType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ancienMotDePasse = $form->get('ancienMotDePasse')->getData();

            if ($encoder->isPasswordValid($user, $ancienMotDePasse)) {
                $newEncodedPassword = $encoder->encodePassword($user, $form->get('nouveauMotDePasse')->getData());
                $user->setPassword($newEncodedPassword);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe à bien été changé !');

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
                $em = $this->getDoctrine()->getManager();
                $em->persist($membre);
                $em->flush();

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

        if(null == $membre) {
            throw new NotFoundHttpException("Ce lien a déjà été utilisé.");
        }

        $form = $this->createForm(NouveauMotDePasseType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newEncodedPassword = $encoder->encodePassword($membre, $form->get('nouveauMotDePasse')->getData());
            $membre->setPassword($newEncodedPassword);
            $membre->setConfirmationToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe à bien été changé !');
            
            return $this->redirectToRoute('aviron_accueil');
        }

        return $this->render('authentification/nouveaumotdepasse.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function DonneMembreParUsername($username)
    {
        $membre = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findOneByUsername($username);

        return $membre;
    }

    private function DonneMembreParToken($token)
    {
        $membre = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findOneByConfirmationToken($token);

        return $membre;
    }

    private function GenereToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
