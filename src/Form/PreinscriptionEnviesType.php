<?php

namespace App\Form;

use App\Entity\EnviesPratique;
use App\Entity\FrequencePratique;
use App\Entity\User;
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
                'class'         => EnviesPratique::class,
                'choice_label'  => 'envie',
                'expanded'      => true,
                'multiple'      => true,
                'label'         => 'Je m\'inscris au Club pour',
                'required'      => false
            ))
            ->add('frequencePratique', EntityType::class, array(
                'class'         => FrequencePratique::class,
                'choice_label'  => 'frequence',
                'expanded'      => true,
                'multiple'      => false,
                'label'         => 'Je pense venir au Club',
                'required'      => false,
                'placeholder'   => 'je ne sais pas encore',
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
            'data_class' => User::class
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
