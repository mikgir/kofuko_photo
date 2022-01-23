<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Выбрать фотографию',
                'required' => false,
                'mapped' => false
            ))
            ->add('title', TextType::class, array(
                'label' => 'Заголовок блога'
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Содержание блога',
                'attr'=>array(
                    'rows'=>'10'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Создать',
                'attr' => array(
                    'class'=>'btn btn-outline-light mt-3 w-100'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
