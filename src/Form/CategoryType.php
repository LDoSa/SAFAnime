<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Anime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('animes', EntityType::class, [
                'class' => Anime::class,
                'choice_label' => 'title',   // Propiedad que se mostrará en el formulario
                'multiple' => true,           // Permite seleccionar varios animes
                'expanded' => true,           // true → checkboxes, false → select múltiple
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
