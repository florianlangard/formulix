<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Prediction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class PredictionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pole', EntityType::class, [
                'class' => Driver::class,
                'constraints' => new NotBlank(),
                'choice_label' => 'fullname',
                'placeholder' => 'Choisissez...',
            ])
            ->add('min', null, [
                'label' => 'Minutes',
                'constraints' => [
                    new Regex('/^\d{1}$/'), 
                    new NotBlank(),
                    
                ],
                'attr' => [
                    'placeholder' => "ex: 1"
                ],
                'mapped' => false,
            ])
            ->add('sec', null, [
                'label' => 'secondes',
                'constraints' => [
                    new Regex('/^\d{2}$/'), 
                    new NotBlank(), 
                    new Range(['min' => 00, 'max' => 59])
                ],
                'attr' => [
                    'placeholder' => "ex: 22"
                ],
                'mapped' => false,
            ])
            ->add('msec', null, [
                'label' => 'millièmes',
                'constraints' => [
                    new Regex('/^\d{3}$/'), 
                    new NotBlank(), 
                    new Range(['min' => 000, 'max' => 999])
                ],
                'attr' => [
                    'placeholder' => "ex: 059"
                ],
                'mapped' => false,
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
