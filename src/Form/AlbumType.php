<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $artists = $options['artists'];

        $builder
            ->add('spotify_album_id')
            ->add('name')
            ->add('release_date')
            ->add('total_tracks')
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
