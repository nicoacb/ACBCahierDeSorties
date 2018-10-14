<?php
namespace Aviron\UserBundle\Controller;

use Aviron\UserBundle\Entity\User;
use Aviron\UserBundle\Form\UserType;
use Aviron\UserBundle\Helper\ChainesHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function indexAction($page)
    {
        if($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $nbParPage = $this->getParameter('nbUsersParPage');

        $listeUsers = $this->getDoctrine()
		->getManager()
		->getRepository('AvironUserBundle:User')
		->getMembres($page, $nbParPage);

        // On calcule le nombre total de pages grâces au count($listeUsers) qui retourne 
        // le nombre total de membres
        $nbPages = ceil(count($listeUsers) / $nbParPage);
		 
        // Si la page n'existe pas, on lève une erreur 404
        if($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

		return $this->render('AvironUserBundle:User:index.html.twig', 
            array(
                'listeUsers'    => $listeUsers,
                'nbPages'       => $nbPages,
                'page'          => $page
            ));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function ajouterAction(Request $request)
    {
        // On créé un objet User
        $user = new User();

        // On génère le formulaire
        $form = $this->createForm(UserType::class, $user);

        // Si la requête est en POST, c'est qu'on veut enregistrer les modifications sur le membre
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {

                $login = ChainesHelper::getLoginFromPrenomNom($user->getPrenom(), $user->getNom());
                $user->setUsername($login);
                $user->setUsernameCanonical($login);
                $email = $login . '@aviron-bourges.org';
                $user->setEmail($email);
                $user->setEmailCanonical($email);
                $user->setEnabled(1);
                $user->setPlainPassword('@viron!');
          
                // On enregistre l'objet $user en base de données
                $userManager = $this->get('fos_user.user_manager'); 
                $userManager->updateUser($user);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Membre bien ajouté.');

                // On redirige vers la liste des membres
                return $this->redirectToRoute('aviron_users_liste');
            }
        }

        return $this->render('AvironUserBundle:User:ajouter.html.twig',
                                array('form' => $form->createView()));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function modifierAction(Request $request, $id)
    {
        // On récupère l'entité du membre correspondante à l'id $id
        $user = $this->getUserById($id);

        // On génère le formulaire
        $form = $this->createForm(UserType::class, $user);

        // Si la requête est en POST, c'est qu'on veut enregistrer les modifications sur le membre
        if($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            if($form->isValid()) {
                // On enregistre l'objet $user en base de données
                $this->persistUser($user);

                // On affiche un message de validation
                $request->getSession()->getFlashBag()->add('success', 'Membre bien modifié.');

                // On redirige vers la liste des membres
                return $this->redirectToRoute('aviron_users_liste');
            }
        }

        return $this->render('AvironUserBundle:User:modifier.html.twig',
                                array('form' => $form->createView()));
    }

    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function supprimerAction(Request $request, $id)
    {
        // On récupère l'entité de l'utilisateur correspondant à l'id $id
        $user = $this->getUserById($id);

        $user->setDatesupp(new \DateTime("now"));

        // On enregistre l'objet $user en base de données
        $this->persistUser($user);

        // On affiche un message de validation
        $request->getSession()->getFlashBag()->add('success', 'Membre bien supprimé.');

        // On redirige vers la liste des membres
        return $this->redirectToRoute('aviron_users_liste');
    }

    private function getUserById($id)
    {
        // On récupère l'entité du membre correspondante à l'id $id
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('AvironUserBundle:User')
            ->find($id);

        // Si $user est null, l'id n'existe pas
        if(null == $user) {
            throw new NotFoundHttpException("Le membre d'id ".$id." n'existe pas.");
        }

        return $user;
    }

    private function persistUser($user)
    {
        // On enregistre l'objet $user en base de données
        $em = $this->GetDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}