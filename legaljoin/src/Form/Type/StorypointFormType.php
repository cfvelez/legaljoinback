<?php

namespace App\Form\Type;

use App\Form\Model\StorypointDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorypointFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('id', NumberType::class)
                ->add('name', TextType::class)
                ->add('description', TextType::class)
                ->add('appointmentAt',  DateTimeType::class,
                        [
                            'format' => 'yyyy-MM-dd  HH:mm:ss', 
                            'widget' => 'single_text',
                            'html5' => false
                        ]
                )->add('storyId', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StorypointDto::class,
            'csrf_protection' => false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}