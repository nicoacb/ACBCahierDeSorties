<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SortieEndType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('bateau')
            ->remove('date')
            ->remove('hdepart')
            ->remove('athletes');
    }
    
    public function getParent()
    {
        return SortieType::class;
    }

}
