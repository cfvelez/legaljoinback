<?php

namespace App\Form\Type;

use App\Form\Model\ResourceDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('title', TextType::class)
                ->add('ownner_id', NumberType::class)
                ->add('storypoint_id', NumberType::class)
                ->add('base64File', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResourceDto::class,
            'csrf_protection' => false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }

    public function getTitle()
    {
        return '';
    }

    public function getStorypoint()
    {
        return null;
    }
}