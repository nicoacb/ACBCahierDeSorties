<?php

namespace App\Form;

use App\Entity\MembreContacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeContact', ChoiceType::class, array(
                'choices'   => array(
                    'Numéro de portable'                    => 1,
                    'Personne à prévenir en cas d\'urgence' => 2,
                    'Mère'                                  => 3,
                    'Père'                                  => 4,
                    'Responsable légal'                     => 5,
                    'Autre'                                 => 6
                ),
                'expanded'  => false,
                'multiple'  => false,
                'required'  => true,
                'placeholder'    => 'Faites votre choix',
                'label'     => 'Type de contact'
            ))
            ->add('telephone', TextType::class, array(
                'label'         => 'Numéro de téléphone'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembreContacts::class,
        ]);
    }
}
