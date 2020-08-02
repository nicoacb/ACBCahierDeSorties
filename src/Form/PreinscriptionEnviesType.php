<?php

namespace App\Form;

use App\Form\EngagementAssociationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreinscriptionEnviesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enviesPratiques', EntityType::class, array(
                'class'         => 'App:EnviesPratique',
                'choice_label'  => 'envie',
                'expanded'      => true,
                'multiple'      => true,
                'label'         => 'Je souhaite pratiquer l\'aviron'
            ))
            ->add('engagementAssociation', EngagementAssociationType::class, array(
                'label'         => 'Mon engagement dans l\'association'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'PreinscriptionEnvies';
    }
}
