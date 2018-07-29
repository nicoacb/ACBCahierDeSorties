<?php

namespace Aviron\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('civilite', ChoiceType::class, array(
            'choices' => array(
                'Monsieur' => 1,
                'Madame' => 2)
        ))
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('datedenaissance', BirthdayType::class, array('required' => false))
        ->add('nationalite', TextType::class, array('required' => false))
        ->add('adresse', TextType::class, array('required' => false))
        ->add('adresse2', TextType::class, array('required' => false))
        ->add('ville', TextType::class, array('required' => false))
        ->add('codepostal', TextType::class, array('required' => false))
        ->add('etablissementscolaire', TextType::class, array('required' => false))
        ->add('classe', TextType::class, array('required' => false))
        ->add('canrow', CheckboxType::class, array('required' => false))
        ->add('roles')
        ->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aviron\UserBundle\Entity\User'
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
