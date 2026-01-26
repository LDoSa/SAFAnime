<?php

namespace App\Form;

use App\Entity\Anime;
use App\Entity\Opinion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texto')
            ->add('puntuacion')
            //->add('user', EntityType::class, [
                //'class' => User::class,
                //'choice_label' => 'id',
            //])
            ->add('anime', EntityType::class, [
                'class' => Anime::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
