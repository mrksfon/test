<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter email'
                    ]),
                    new Email([
                        'message' => 'The email "{{ value }}" is not a valid email.'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Email could not be longer than 255 characters'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 255,
                        'minMessage' => 'Password could not be shorter than 8 characters',
                        'maxMessage' => 'Password could not be longer than 255 characters'
                    ]),
                ]
            ])
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'First name could not be shorter than 2 characters',
                        'maxMessage' => 'First name could not be longer than 255 characters'
                    ])
                ]
            ])
            ->add('last_name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Last name could not be shorter than 2 characters',
                        'maxMessage' => 'Last name could not be longer than 255 characters'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
