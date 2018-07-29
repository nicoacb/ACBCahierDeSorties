<?php

namespace Aviron\SortieBundle\Form\DataTransformer;

use Aviron\UserBundle\Entity\User;
/*use Doctrine\ORM\EntityManager;*/
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AthleteToIdTransformer implements DataTransformerInterface
{
    /*private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }*/

    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    

    /**
     * Transforms an object (user) to a string (id).
     *
     * @param  User|null $user
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }

        return $user->getId();
    }

    /**
     * Transforms a string (id) to an object (user).
     *
     * @param  string $userId
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($userId)
    {
        // no user number? It's optional, so that's ok
        if (!$userId || $userId->getId() == -1 || $userId->getId() == -2) {
            return;
        }

        

        $user = $this->em
            ->getRepository(User::class)
            // query for the user with this id
            ->find($userId)
        ;

        if (null === $user) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An user with number "%s" does not exist!',
                $userId
            ));
        }

        return $user;
    }
}