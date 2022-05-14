<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Prediction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RacePredictionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('time')
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('score')
            // ->add('pole')
            // ->add('user')
            // ->add('event')
            ->add('finishFirst', EntityType::class, [
                'class' => Driver::class,
                'constraints' => new NotBlank(),
                'label' => 'Vainqueur',
                'choice_label' => 'fullname',
                'placeholder' => 'Choisissez...',
            ])
            ->add('finishSecond', EntityType::class, [
                'class' => Driver::class,
                'constraints' => new NotBlank(),
                'label' => '2ème position',
                'choice_label' => 'fullname',
                'placeholder' => 'Choisissez...',
            ])
            ->add('finishThird', EntityType::class, [
                'class' => Driver::class,
                'constraints' => new NotBlank(),
                'label' => '3ème position',
                'choice_label' => 'fullname',
                'placeholder' => 'Choisissez...',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prediction::class,
        ]);
    }
}
