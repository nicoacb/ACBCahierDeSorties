<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeBateauType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label'         => 'Type de bateau'
            ))
            ->add('nbplacerameurs', IntegerType::class, array(
                'label'         => 'Nombre de places de rameur'
            ))
            ->add('nbplacebarreurs', IntegerType::class, array(
                'label'         => 'Nombre de places de barreur'
            ))
            ->add('supp', CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class, array(
                'label'         => 'Enregistrer'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\TypeBateau'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_sortiebundle_typebateau';
    }


}
