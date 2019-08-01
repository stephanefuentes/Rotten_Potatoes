<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\People;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du film',
                'attr' => ['placeholder' => 'Titre du film ici ...']
            ])
            //->add('slug')
            ->add('poster', UrlType::class, [
                'label' => 'Image du film',
                'attr' => ['placeholder' => 'Url de l image ici ...']
            ])
            //->add('releasedAt')
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
                'attr' => ['placeholder' => 'Url de l image ici ...',
                            'rows' => '20'
                            ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true

            ])
            ->add('actors',  EntityType::class, [
                'label' => 'Acteurs',
                'class' => People::class,
                'choice_label' => 'fullName',
                'multiple' => true
                // 'expanded' => true

            ])
            ->add('director',  EntityType::class, [
                'label' => 'Directeur',
                'class' => People::class,
                'choice_label' => 'fullName'
                // 'multiple' => true,
                //'expanded' => true

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
