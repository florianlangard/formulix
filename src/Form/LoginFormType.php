<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
                if ($user->getTwitchId() === null) {
                    $form->add('email', EmailType::class, [
                        'label' => 'adresse email'
                    ])
                    ->add('personaname', TextType::class, [
                        'label' => 'Votre pseudo'
                    ]);
                }
                else
                {
                    $form->add('email', EmailType::class, [
                        'label' => 'adresse email',
                        'disabled' => true,
                        'attr' => [
                            'readonly' => true,
                        ]
                    ])
                    ->add('personaname', TextType::class, [
                        'label' => 'Votre pseudo',
                        'disabled' => true,
                        'attr' => [
                            'readonly' => true,
                        ]
                    ]);
                }
                
            })
            // ->add('email', EmailType::class, [
            //     'label' => 'adresse email'
            // ])
            // ->add('personaname', TextType::class, [
            //     'label' => 'Votre pseudo'
            // ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
                if ($user->getId() === null) 
                {
                    $form->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'les mots de passe ne correspondent pas',
                        'first_options'  => [
                            'constraints' => new NotBlank(),
                            'label' => 'Mot de passe',
                            'help' => 'Minimum huit caractères, au moins une lettre, un nombre et un caractère spécial.'
                        ],
                        'second_options' => ['label' => 'Répéter le mot de passe'],
                    ]);
                }
                else
                {
                    $form->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'mapped' =>false,
                        'required' => false,
                        'invalid_message' => 'les mots de passe ne correspondent pas',
                        'first_options'  => [
                            
                            'label' => 'Mot de passe',
                            'help' => 'Minimum huit caractères, au moins une lettre, un nombre et un caractère spécial.'
                        ],
                        'second_options' => ['label' => 'Répéter le mot de passe'],
                    ]);
                }
            });
            
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
