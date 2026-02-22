<?php

namespace App\Form;

use App\Entity\Anime;
use App\Entity\Opinion;
use App\Entity\User;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('puntuacion', ChoiceType::class, [
                'label' => 'Puntuación',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('texto', TextareaType::class, [
                'label' => 'Escribe que te ha parecido (opcional)',
                'required' => false,])

            //->add('user', EntityType::class, [
                //'class' => User::class,
                //'choice_label' => 'id',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
