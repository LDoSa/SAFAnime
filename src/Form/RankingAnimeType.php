<?php

namespace App\Form;

use App\Entity\RankingAnime;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RankingAnimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('position', IntegerType::class, [
                'label' => 'Posición',
                'attr' => [
                    'min' => 1,
                    'max' => $options['max_position'] ?? 10,
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RankingAnime::class,
            'max_positions' => null,
        ]);
    }
}
