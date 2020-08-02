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
                'label'         => 'Brevet de secourisme (baignade, plongée, 1er  secours,..), précisez ',
                'required'  => false
            ))
            ->add('encadrementSportif', TextType::class, array(
                'label'         => 'Encadrement sportif (brevet  fédéral,…), précisez',
                'required'  => false
            ))
            ->add('communication', TextType::class, array(
                'label'         => 'Communication (rédaction, flyer, photo, vidéo,…), précisez',
                'required'  => false
            ))
            ->add('informatique', TextType::class, array(
                'label'         => 'Informatique (développement, réseau,…), précisez',
                'required'  => false
            ))
            ->add('technique', TextType::class, array(
                'label'         => 'Technique (mécanique nautique, soudure,…), précisez',
                'required'  => false
            ))
            ->add('autres', TextType::class, array(
                'label'         => 'Autres, précisez',
                'required'  => false
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
