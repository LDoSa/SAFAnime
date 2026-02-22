<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ranking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RankingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rankingAnimes', CollectionType::class, [
                'entry_type' => RankingAnimeType::class,
                'entry_options' => ['max_positions' => $options['max_positions']],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ranking::class,
            'max_positions' => 30,
        ]);
    }
}
