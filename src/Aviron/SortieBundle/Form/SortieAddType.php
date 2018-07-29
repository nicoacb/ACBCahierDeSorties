<?php

namespace Aviron\SortieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SortieAddType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('hretour', TimeType::class)
            ->remove('kmparcourus', NumberType::class)
            ->remove('observations', TextareaType::class);
    }

    public function getParent()
    {
        return SortieType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aviron_sortiebundle_sortie_add';
    }
}
