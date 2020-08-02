<?php

namespace App\Form;

use App\Form\MembreContactsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreinscriptionContactsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label'         => 'L\'adresse email qui nous permettra de vous contacter',
                'required'  => true
            ))
            ->add('contacts', CollectionType::class, array(
                'entry_type'    => MembreContactsType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                'label'         => 'Numéros de téléphone :',
                'by_reference'  => false
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
        return 'PreinscriptionContacts';
    }
}
