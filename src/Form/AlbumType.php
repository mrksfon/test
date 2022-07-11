<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $artists = $options['artists'];

        $builder
            ->add('spotify_album_id', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Spotify Album Id could not be empty'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Spotify Album id could not have more than 255 characters'
                    ])
                ]
            ])
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
            ->add('release_date')
            ->add('total_tracks', IntegerType::class, [
                'constraints' => [
                    new Positive([
                        'message' => 'Number of total tracks must be positive'
                    ])
                ]
            ])
            ->add('artist', ChoiceType::class, [
                'choices' => [
                    $artists
                ],
                'choice_label' => function (?Artist $artist) {
                    return $artist ? strtoupper($artist->getName()) : '';
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
            'artists' => []
        ]);
    }
}
