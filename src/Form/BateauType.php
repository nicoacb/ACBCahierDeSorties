<?php

namespace App\Form;

use App\Repository\TypeBateauRepository;
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
            ->add('nom', TextType::class, array(
                'label'         => 'Nom du bateau'
            ))
            ->add('type', EntityType::class, array(
                'class'         => 'App:TypeBateau',
                'choice_label'  => 'nom',
                'multiple'      => false,
                'label'         => 'Type de bateau',
                'query_builder' => function(TypeBateauRepository $tbrepository) {
                    return $tbrepository->getListeTypesBateauQueryBuilder();
                }
            ))
            ->add('fabriquant', TextType::class, array(
                'required' => false,
                'label'         => 'Fabriquant'
            ))
            ->add('gamme', TextType::class, array(
                'required' => false,
                'label'         => 'Gamme'
            ))
            ->add('datefabrication', DateType::class, array(
                'required' => false,
                'label'         => 'Date de fabrication'
            ))
            ->add('dateachat', DateType::class, array(
                'required' => false, 
                'label'         => 'Date d\'achat'
            ))
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
            'data_class' => 'App\Entity\Bateau'
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
