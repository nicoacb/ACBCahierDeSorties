<?php

namespace App\Form;

use App\Entity\MembreLicences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accepteReglementInterieur', CheckboxType::class, array(
                'label'     => 'J\'ai pris connaissance du règlement intérieur et l\'accepte sans réserve',
                'required'  => true
            ))
            ->add('autoriseDroitImage', ChoiceType::class, array(
                'choices'   => array(
                    'J\'autorise l\'Aviron Club de Bourges à disposer de mon droit à l\'image pour la promotion de son activité.'    => true,
                    'Je n\'autorise pas l\'Aviron Club de Bourges à disposer de mon droit à l\'image pour la promotion de son activité.'  => false
                ),
                'expanded'  => true,
                'multiple'  => false,
                'required'  => true,
                'label'     => 'Droit à l\image'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembreLicences::class,
        ]);
    }
}
