<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('followers', IntegerType::class, [
                'constraints' => [
                    new Positive([
                        'message' => 'The number of followers must be positive'
                    ])
                ]
            ])
            ->add('spotify_artist_id', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Spotify artist id could not be empty',
                    ])
                ]
            ])
            ->add('image_link')
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Name could not be empty'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'The name could not be shorter than 2 characters',
                        'maxMessage' => 'The name could not be longer than 255 characters'
                    ])
                ]
            ])
            ->add('popularity', IntegerType::class, [
                'constraints' => [
                    new Positive([
                        'message' => 'The number of popularity must be positive'
                    ])
                ]
            ])
            ->add('genre');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
