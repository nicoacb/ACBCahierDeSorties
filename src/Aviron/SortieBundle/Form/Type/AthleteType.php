<?php

namespace Aviron\SortieBundle\Form\Type;

use Aviron\UserBundle\Entity\User;
use Aviron\UserBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class AthleteType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class'             => 'AvironUserBundle:User',
            'choice_label'  => 'prenomnom',
            'choice_value' => function (User $entity = null) {
                return $entity ? $entity->getId() : 0;
            },
            'multiple'      => false,
            'query_builder' => function(UserRepository $repository) {
                return $repository->getCanRowQueryBuilder();
            },
            'attr'          => array(
                'class' => 'selectpicker',
                'data-live-search' => 'true',
                'data-live-search-normalize' => 'true'
            ),
            'placeholder'   => 'Sélectionnez les membres de votre équipage',
            'validation_groups' => false,
            'inherit_data' => false            
        ));
    
    }

    public function getParent()
    {
        return EntityType::class;
    }
}