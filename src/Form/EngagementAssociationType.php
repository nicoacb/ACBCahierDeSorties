<?php

namespace App\Form;

use App\Entity\EngagementAssociation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EngagementAssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aPermisBateauEauxInterieures', CheckboxType::class, array(
                'label'     => 'J\'ai le permis bateaux Eaux Intérieures',
                'required'  => false
            ))
            ->add('aPermisBateauCotier', CheckboxType::class, array(
                'label'     => 'J\'ai le permis bateaux Côtier',
                'required'  => false
            ))
            ->add('aPermisRemorqueEB', CheckboxType::class, array(
                'label'     => 'J\'ai le permis remorque EB',
                'required'  => false
            ))
            ->add('aPermisRemorqueB96', CheckboxType::class, array(
                'label'     => 'J\'ai le permis remorque B96',
                'required'  => false
            ))
            ->add('brevetSecourisme', TextType::class, array(
                'label'         => 'Brevet de secourisme',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Baignade, plongée, 1er  secours...'
                )
            ))
            ->add('encadrementSportif', TextType::class, array(
                'label'         => 'Encadrement sportif',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Brevets  fédéraux, licences STAPS, BPJEPS...'
                )
            ))
            ->add('communication', TextType::class, array(
                'label'         => 'Communication',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Rédaction, flyer, photo, vidéo...'
                )
            ))
            ->add('informatique', TextType::class, array(
                'label'         => 'Informatique',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Développement, réseau...'
                )
            ))
            ->add('technique', TextType::class, array(
                'label'         => 'Technique',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Peinture, mécanique nautique, soudure...'
                )
            ))
            ->add('autres', TextType::class, array(
                'label'         => 'Autres',
                'required'  => false,
                'attr'   => array(
                    'placeholder' => 'Précisez'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EngagementAssociation::class,
        ]);
    }
}
