<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MotDePasseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $ancienMotDePasse = '';

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
            'form' => $form->createView(), 'ancien' => $ancienMotDePasse
        ));
    }
}
