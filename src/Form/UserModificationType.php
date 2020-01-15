<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserModificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', TextType::class, array(
            'label'         => 'Prénom'
        ))
            ->add('nom', TextType::class, array(
                'label'         => 'Nom'
            ))
            ->add('username', TextType::class, array(
                'label'         => 'Login',
                'required'      => false
            ))
            ->add('email', TextType::class, array(
                'label'         => 'Email',
                'required'      => false
            ))
            ->add('saisons', EntityType::class, array(
                'class'         => 'App:Saison',
                'choice_label'  => 'nom',
                'expanded'      => true,
                'multiple'      => true,
            ))
            ->add('roles', ChoiceType::class, array(
                'choices'   => array(
                    'Administrateur'                        => 'ROLE_ADMIN',
                    'Membre (nécessaire pour se connecter)' => 'ROLE_USER'
                ),
                'expanded'  => true,
                'multiple'  => true,
                'required'  => true,
                'label'     => 'Droits'
            ))
            ->add('save', SubmitType::class, array(
                'label'         => 'Enregistrer'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_userbundle_user';
    }
}
