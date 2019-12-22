<?php

namespace Aviron\SortieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label'         => 'Libellé de l\'entraînement'
            ))
            ->add('datedebut', DateType::class, array(
                'label'         => 'Date de début'
            ))
            ->add('datefin', DateType::class, array(
                'required' => false, 
                'label'         => 'Date de fin'
            ))
            ->add('heuredebut', TimeType::class, array(
                'label' => 'Début de l\'entraînement à'
            ))
            ->add('heurefin', TimeType::class, array(
                'label' => 'Fin de l\'entraînement à'
            ))
            ->add('datecloture', DateTimeType::class, array(
                'label' => 'Clôture des inscriptions'
            ))
            ->add('nbplacesdisponibles', NumberType::class, array(
                'label' => 'Nombre de places disponibles'
            ))
            ->add('save', SubmitType::class, array(
                'label'         => 'Enregistrer')
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aviron\SortieBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_sortiebundle_reservation';
    }


}
