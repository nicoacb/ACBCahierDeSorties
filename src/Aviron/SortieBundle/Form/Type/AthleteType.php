<?php

namespace Aviron\SortieBundle\Form\Type;

use Aviron\SortieBundle\Form\DataTransformer\AthleteToIdTransformer;
use Aviron\UserBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class AthleteType extends AbstractType
{
    /**
    * @var \Doctrine\ORM\EntityManager
    */
    protected $em;
     
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new AthleteToIdTransformer($this->em));
        /*$builder->addModelTransformer($this->transformer);*/
    }
     

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class'             => 'AvironUserBundle:User',
            'choice_label'  => 'prenomnom',
            'multiple'      => false,
            'query_builder' => function(UserRepository $repository) {
                return $repository->getCanRowQueryBuilder();
            },
            'attr'          => array(
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ),
            'placeholder'   => 'Sélectionnez les membres de votre équipage',
            'validation_groups' => false,
            'inherit_data' => false            
        ));
    
    }

    public function getParent()
    {
        return EntityType::class;
    }
}