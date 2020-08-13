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
                'choices'       => array(
                    'Numéro de portable'                                                => 1,
                    'Numéro de portable de la personne à prévenir en cas d\'urgence'    => 2,
                    'Numéro de portable de la Mère'                                     => 3,
                    'Numéro de portable du Père'                                        => 4,
                    'Numéro de portable du Responsable légal'                           => 5,
                    'Autre (précisez dans le champ Nom  du contact'                     => 6,
                    'Email de la Mère'                                                  => 7,
                    'Email du Père'                                                     => 8,
                    'Email du Responsable légal'                                        => 9,
                ),
                'expanded'      => false,
                'multiple'      => false,
                'required'      => true,
                'placeholder'   => 'Faites votre choix',
                'label'         => 'Type de contact*'
            ))
            ->add('nomContact', TextType::class, array(
                'label'         => 'Nom du contact*',
                'attr'          => array(
                    'placeholder' => 'Nom du contact'
                )
            ))
            ->add('telephoneEmail', TextType::class, array(
                'label'         => 'Numéro de téléphone ou email*',
                'attr'          => array(
                    'placeholder' => 'Numéro de téléphone ou email'
                )
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
