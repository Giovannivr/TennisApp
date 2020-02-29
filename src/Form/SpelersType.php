<?php

namespace App\Form;

use App\Entity\Spelers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SpelersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voornaam')
            ->add('tussenvoegsel')
            ->add('achternaam')
            ->add('schoolId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Spelers::class,
        ]);
    }
}
