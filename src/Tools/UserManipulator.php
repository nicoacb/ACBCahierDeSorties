<?php

namespace App\Tools;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\UserManipulator as Manipulator;

/**
 * Executes some manipulations on the users
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class UserManipulator extends Manipulator
{
    /**
     * User manager
     *
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Creates a user and returns it.
     *
     * @param string  $username
     * @param string  $password
     * @param string  $email
     * @param Boolean $active
     * @param Boolean $superadmin
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function create($username, $password, $email, $active, $superadmin)
    {

        $user = $this->userManager->createUser();
        $user->setUsername($username);
        $user->setNom($this->nom);
        $user->setPrenom($this->prenom);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) $active);
        $this->userManager->updateUser($user, true);

        return $user;
    }
    
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

}