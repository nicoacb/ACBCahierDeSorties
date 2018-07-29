<?php

namespace Aviron\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class);
        $builder->add('prenom', TextType::class);
        $builder->add('datedenaissance');
        $builder->add('nationalite', TextType::class);
        $builder->add('adresse');
        $builder->add('adresse2');
        $builder->add('ville', TextType::class);
        $builder->add('codepostal');
        $builder->add('etablissementscolaire');
        $builder->add('classe');
        $builder->add('canrow');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
