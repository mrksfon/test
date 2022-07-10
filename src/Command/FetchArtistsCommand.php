<?php

namespace App\Command;

use App\Entity\Album;
use App\Entity\Artist;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class FetchArtistsCommand extends Command
{
    private $client;
    private $doctrine;

    public function __construct(HttpClientInterface $client, ManagerRegistry $doctrine)
    {
        $this->client = $client;
        $this->doctrine = $doctrine;

        parent::__construct();
    }

    protected static $defaultName = 'fetch_artist';
    protected static $defaultDescription = 'Fetch first 20 artists and their albums base on first name of the artist';

    protected function configure(): void
    {
        $this
            ->addArgument('searchParam', InputArgument::REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note(sprintf('Enter a name of the artist'));

        $searchParam = $input->getArgument('searchParam');

        // $io->success($searchParam);

        // $io->error('Marko je car');
        // dd($_ENV['SPOTIFY_API_KEY']);
        // dd(env('SPOTIFY_API_KEY'));


        try {
            $response = $this->client->request(
                'GET',
                'https://api.spotify.com/v1/search',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $_ENV['SPOTIFY_API_KEY'],
                    ],
                    'query' => [
                        'q' => $searchParam,
                        'type' => 'artist',
                        'offset' => 0,
                        'limit' => 20,
                    ]
                ]
            );

            $content = $response->getContent();

            $content = json_decode($content);

            $entityManager = $this->doctrine->getManager();

            foreach ($content->artists->items as $key => $spotifyArtist) {
                $artist = new Artist();
                $artist->setFollowers($spotifyArtist->followers->total);
                $artist->setSpotifyArtistId($spotifyArtist->id);

                if (count($spotifyArtist->images) == 0) {
                    $artist->setImageLink('https://storage.googleapis.com/pr-newsroom-wp/1/2018/11/Spotify_Logo_CMYK_Green.png');
                } else {
                    $artist->setImageLink($spotifyArtist->images[0]->url);
                }
                $artist->setName($spotifyArtist->name);
                $artist->setPopularity($spotifyArtist->popularity);

                $genresString = "";
                foreach ($spotifyArtist->genres as $genre) {
                    $genresString .= $genre . ',';
                }
                $artist->setGenre($genresString);



                $responseAlbums = $this->client->request(
                    'GET',
                    'https://api.spotify.com/v1/artists/' . $spotifyArtist->id . '/albums',
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $_ENV['SPOTIFY_API_KEY'],
                        ],
                        'query' => [
                            'offset' => 0,
                            'limit' => 20,
                        ]

                    ]
                );



                $contentAlbums = $responseAlbums->getContent();

                $contentAlbums = json_decode($contentAlbums);

                $entityManager->persist($artist);
                $entityManager->flush();

                foreach ($contentAlbums->items as $key => $spotifyAlbum) {
                    $album = new Album();
                    $album->setSpotifyAlbumId($spotifyAlbum->id);;
                    $album->setName($spotifyAlbum->name);
                    $album->setReleaseDate($spotifyAlbum->release_date);
                    $album->setTotalTracks($spotifyAlbum->total_tracks);
                    $album->setArtist($artist);

                    $entityManager->persist($album);
                    $entityManager->flush();
                }
            }
        } catch (Exception $ex) {
            $io->error($ex->getMessage());
            return Command::FAILURE;
        }



        $io->success('Sucessfully saved to database');

        return Command::SUCCESS;
    }
}
