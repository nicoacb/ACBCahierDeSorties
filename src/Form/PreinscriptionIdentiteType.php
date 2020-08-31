<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreinscriptionIdentiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, array(
                'choices'   => array(
                    'Madame'    => 1,
                    'Monsieur'  => 2
                ),
                'expanded'      => true,
                'multiple'      => false,
                'required'      => true,
                'placeholder'   => 'Faites votre choix',
                'label'         => 'Civilité*',
                'label_attr'    => [
                    'class' => 'radio-inline'
                ]
            ))
            ->add('prenom', TextType::class, array(
                'label'     => 'Prénom*',
                'required'  => true
            ))
            ->add('nom', TextType::class, array(
                'label'     => 'Nom*',
                'required'  => true
            ))
            ->add('datenaissance', BirthdayType::class, array(
                'label'         => 'Date de naissance*',
                'placeholder'   => ['year' => 'année', 'month' => 'mois', 'day' => 'jour'],
                'required'      => true
            ))
            ->add('nationalite', EntityType::class, array(
                'class'         => 'App:MembreNationalite',
                'choice_label'  => 'nationalite',
                'expanded'      => false,
                'multiple'      => false,
                'label'         => 'Nationalité*',
                'required'      => true
            ))
            ->add('numeroVoie', TextType::class, array(
                'label'     => 'Adresse*',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'N°'
                )
            ))
            ->add('typeVoie', ChoiceType::class, array(
                'choices'   => array(
                    'Allée' => 'Allee',
                    'Avenue' => 'Avenue',
                    'Boulevard' => 'Boulevard',
                    'Carrefour' => 'Carrefour',
                    'Chaussée' => 'Chaussee',
                    'Chemin' => 'Chemin',
                    'Cité' => 'Cite',
                    'Clos' => 'Clos',
                    'Cours' => 'Cours',
                    'Domaine' => 'Domaine',
                    'Enclos' => 'Enclos',
                    'Faubourg' => 'Faubourg',
                    'Hameau' => 'Hameau',
                    'Impasse' => 'Impasse',
                    'Jardin' => 'Jardin',
                    'Lotissement' => 'Lotissement',
                    'Mail' => 'Mail',
                    'Montée' => 'Montee',
                    'Parc' => 'Parc',
                    'Passage' => 'Passage',
                    'Place' => 'Place',
                    'Quai' => 'Quai',
                    'Quartier' => 'Quartier',
                    'Route' => 'Route',
                    'Rue' => 'Rue',
                    'Ruelle' => 'Ruelle',
                    'Sente' => 'Sente',
                    'Sentier' => 'Sentier',
                    'Square' => 'Square',
                    'Traverse' => 'Traverse',
                    'Villa' => 'Villa',
                    'Village' => 'Village',
                    'Voie' => 'Voie'
                ),
                'expanded'      => false,
                'multiple'      => false,
                'placeholder'   => 'Type de voie',
                'label'         => 'Type de voie*',
                'required'      => false
            ))
            ->add('libelleVoie', TextType::class, array(
                'label'     => 'Nom de la voie*',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Nom de la voie'
                )
            ))
            ->add('immBatRes', TextType::class, array(
                'label'     => 'Immeuble, bâtiment, résidence',
                'required'  => false
            ))
            ->add('aptEtageEsc', TextType::class, array(
                'label'     => 'Appartement, étage, escalier',
                'required'  => false
            ))
            ->add('lieuDit', TextType::class, array(
                'label'     => 'Lieu dit*',
                'required'  => false
            ))
            ->add('codePostal', TextType::class, array(
                'label'     => 'Code Postal*',
                'required'  => true
            ))
            ->add('ville', TextType::class, array(
                'label'     => 'Ville*',
                'required'  => true
            ))
            ->add('licence', NumberType::class, array(
                'label'     => 'Numéro de licence',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Uniquement si vous avez déjà pris une licence dans un autre club'
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'PreinscriptionIdentite';
    }
}
