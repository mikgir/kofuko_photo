<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array(
                'label'=>'Заголовок'
            ))
            ->add('description', TextareaType::class, array(
                'label'=>'Описание',
                'attr'=>[
                    'rows'=>'8'
                ]
            ))
            ->add('dueDate', DateType::class, array(
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'years' => range(2022, 2030),
                'label'=>'Выполнить до:'
            ))
            ->add('submit', SubmitType::class, array(
                'label'=>'добавить',
                'attr'=>array(
                    'class'=>'btn btn-outline-light mt-3 w-100'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
