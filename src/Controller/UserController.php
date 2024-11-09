<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserModificationType;
use App\Helper\ChainesHelper;
use App\Repository\SaisonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UserController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $saisonRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        SaisonRepository $saisonRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->saisonRepository = $saisonRepository;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function index($page, $saison)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }
        $nbParPage = $this->getParameter('nbUsersParPage');        

        if ($saison == 0) {
            $saison = $this->DonneDerniereSaison()->GetId();
        }

        $listeUsers = $this->userRepository->DonneMembres($page, $nbParPage, $saison);

        // On calcule le nombre total de pages grâces au count($listeUsers) qui retourne 
        // le nombre total de membres
        $nbPages = ceil(count($listeUsers) / $nbParPage);
        if ($nbPages == 0) $nbPages = 1;

        // Si la page n'existe pas, on lève une erreur 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $saisons = $this->DonneSaisons();

        return $this->render(
            'user/index.html.twig',
            array(
                'listeUsers'            => $listeUsers,
                'nbPages'               => $nbPages,
                'page'                  => $page,
                'saisons'               => $saisons,
                'saisonSelectionnee'    => $saison
            )
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function ajouter(Request $request, UserPasswordEncoderInterface $encoder)
    {
        // On créé un objet User
        $user = new User();

        // On génère le formulaire
        $form = $this->createForm(UserType::class, $user);

        // Si la requête est en POST, c'est qu'on veut enregistrer les modifications sur le membre
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {

                $login = ChainesHelper::getLoginFromPrenomNom($user->getPrenom(), $user->getNom());
                $user->setUsername($login);
                $email = $login . '@aviron-bourges.org';
                $user->setEmail($email);
                $user->setEnabled(1);
                $user->setPassword($encoder->encodePassword($user, bin2hex(random_bytes(12))));

                $this->EnregistreMembre($user);

                $this->addFlash('success', 'Membre bien ajouté.');

                // On redirige vers la liste des membres
                return $this->redirectToRoute('aviron_users_liste');
            }
        }

        return $this->render(
            'user/ajouter.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function modifier(Request $request, $id)
    {
        // On récupère l'entité du membre correspondante à l'id $id
        $user = $this->DonneMembre($id);

        // On génère le formulaire
        $form = $this->createForm(UserModificationType::class, $user);

        // Si la requête est en POST, c'est qu'on veut enregistrer les modifications sur le membre
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $user en base de données
                $this->EnregistreMembre($user);

                $this->addFlash('success', 'Membre bien modifié.');

                // On redirige vers la liste des membres
                return $this->redirectToRoute('aviron_users_liste');
            }
        }

        return $this->render(
            'user/modifier.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function supprimer(Request $request, $id)
    {
        // On récupère l'entité de l'utilisateur correspondant à l'id $id
        $user = $this->DonneMembre($id);

        $user->setDatesupp(new \DateTime("now"));

        // On enregistre l'objet $user en base de données
        $this->EnregistreMembre($user);

        $this->addFlash('success', 'Membre bien supprimé.');

        // On redirige vers la liste des membres
        return $this->redirectToRoute('aviron_users_liste');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function afficherProfil()
    {
        return $this->render(
            'user/profil.html.twig',
            array('user' => $this->getUser())
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function envoyerLoginParMail(Request $request, MailerInterface $mailer, $id)
    {
        $membre = $this->DonneMembre($id);

        $email = (new TemplatedEmail())
            ->from(new Address('nepasrepondre@avironclub.fr', 'Aviron Club de Bourges'))
            ->to($membre->getEmail())
            ->subject('Votre compte Aviron Club de Bourges')
            ->htmlTemplate('user/envoyerloginparmail.html.twig')
            ->context([
                'membre' => $membre
            ]);

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $mailer->send($email);

        $this->addFlash('success', 'Mail envoyé à ' . $membre->getPrenomNom() . ' (' . $membre->getEmail() . ').');

        // On redirige vers la liste des membres
        return $this->redirectToRoute('aviron_users_liste');
    }

    private function DonneMembre($id)
    {
        // On récupère l'entité du membre correspondante à l'id $id
        $user = $this->userRepository->find($id);

        // Si $user est null, l'id n'existe pas
        if (null == $user) {
            throw new NotFoundHttpException("Le membre d'id " . $id . " n'existe pas.");
        }

        return $user;
    }

    private function EnregistreMembre($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function DonneSaisons()
    {
        return $this->saisonRepository->DonneSaisons();
    }

    private function DonneDerniereSaison()
    {
        return $this->saisonRepository->DonneDerniereSaison();
    }
}
