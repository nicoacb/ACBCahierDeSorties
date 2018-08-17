<?php

namespace Aviron\SortieBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BateauType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('datefabrication', DateType::class, array('required' => false))
            ->add('dateachat', DateType::class, array('required' => false))
            ->add('fabriquant', TextType::class, array('required' => false))
            ->add('gamme', TextType::class, array('required' => false))
            ->add('type', EntityType::class, array(
                'class'         => 'AvironSortieBundle:TypeBateau',
                'choice_label'  => 'nom',
                'multiple'      => false
            ))
            ->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aviron\SortieBundle\Entity\Bateau'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_sortiebundle_bateau';
    }


}
