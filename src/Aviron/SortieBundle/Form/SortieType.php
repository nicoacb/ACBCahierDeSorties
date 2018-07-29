<?php

namespace Aviron\SortieBundle\Form;

use Aviron\SortieBundle\Form\Type\AthleteType;
use Aviron\SortieBundle\Repository\BateauRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = $options['nbrameurs'];
        $builder
            ->add('bateau', EntityType::class, array(
                'class'         => 'AvironSortieBundle:Bateau',
                'choice_label'  => 'typenom',
                'multiple'      => false,
                'choice_attr'   => function($val, $key,$index) {
                    return ['nbrameurs' => $val->getNbplacerameurs(), 
                            'nbbarreurs' => $val->getNbplacebarreurs()];
                },
                'attr'          => array(
                    'class' => 'selectpicker',
                    'data-live-search' => 'true'
                ),
                'query_builder' => function(BateauRepository $brepository) use($pattern) {
                      return $brepository->getNbRameursQueryBuilder($pattern);
                },
                'placeholder' => 'Sélectionnez le bateau qui vous a été attribué',
                'label' => 'Bateau :'
            ))
            ->add('date', DateType::class, array(
                'label' => 'Date :'
            ))
            ->add('hdepart', TimeType::class, array(
                'label'         => 'Heure de départ :',
                'minutes'       => range(0, 59, 5)
            ))
            ->add('athletes', CollectionType::class, array(
                'entry_type'    => AthleteType::class,
                'allow_add'     => true,
                'allow_delete'  => false,
                'label'         => 'Équipage :',
                'by_reference'  => false
            ))
            ->add('hretour', TimeType::class, array(
                'label'         => 'Heure de retour :',
                'minutes'       => range(0, 59, 5)
            ))
            ->add('kmparcourus', NumberType::class, array(
                'label'         => 'Nombre de kilomètres parcourus :'
            ))
            ->add('observations', TextareaType::class, array(
                'label'         => 'Observations :',
                'required'      => false
            ))
            ->add('save', SubmitType::class, array(
                'label'         => 'Enregistrer'
            ))
            ->get('athletes')->resetViewTransformers();            
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aviron\SortieBundle\Entity\Sortie',
            'nbrameurs'  => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_sortiebundle_sortie';
    }


}
