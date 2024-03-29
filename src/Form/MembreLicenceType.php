<?php

namespace App\Form;

use App\Entity\MembreLicences;
use App\Entity\Saison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreLicenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('saison', EntityType::class, array(
                'class' => Saison::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('typeLicence', ChoiceType::class, array(
                'choices'   => array(
                    'Licence A' => 1,
                    'Licence U' => 2,
                    'Licence I' => 3,
                    'D90'       => 4,
                    'D30'       => 5,
                    'D7'        => 6
                ),
                'expanded'  => false,
                'multiple'  => false,
                'required'  => true,
                'label'     => 'Type de licence'
            ))
            ->add('avecIASportPlus', CheckboxType::class, array(
                'label'     => 'Avec MAIF IA Sport+',
                'required'  => false
            ))
            ->add('dateCertificatPratique', DateType::class, array(
                'label'     => 'Date du certificat médical de pratique',
                'required'  => false
            ))
            ->add('avecAttestationPratique', CheckboxType::class, array(
                'label'     => 'ou Attestation pour la pratique (renouvellement)',
                'required'  => false
            ))
            ->add('dateCertificatCompetition', DateType::class, array(
                'label'     => 'Date du certificat médical de compétition',
                'required'  => false
            ))
            ->add('avecAttestationCompetition', CheckboxType::class, array(
                'label'     => 'ou Attestation pour la compétition (renouvellement)',
                'required'  => false
            ))
            ->add('save', SubmitType::class, array(
                'label'         => 'Valider l\'inscription'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembreLicences::class,
        ]);
    }
}
