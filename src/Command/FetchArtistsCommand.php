<?php

namespace App\Command;

use App\Entity\Album;
use App\Entity\Artist;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchArtistsCommand extends Command
{
    private $client;
    private $artistRepository;
    private $albumRepository;

    public function __construct(HttpClientInterface $client, ArtistRepository $artistRepository, AlbumRepository $albumRepository)
    {
        $this->client = $client;
        $this->artistRepository = $artistRepository;
        $this->albumRepository = $albumRepository;

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

        try {
            $content = json_decode($this->searchArtists($searchParam));

            $progressBar = new ProgressBar($output, count($content->artists->items));
            $progressBar->start();

            foreach ($content->artists->items as $key => $spotifyArtist) {

                $artist = $this->makeArtist($spotifyArtist);

                $contentAlbums = json_decode($this->searchAlbums($spotifyArtist));

                $contentAlbums = ($contentAlbums);

                $this->artistRepository->add($artist, true);



                foreach ($contentAlbums->items as $key => $spotifyAlbum) {
                    $album = $this->makeAlbum($spotifyAlbum, $artist);

                    $this->albumRepository->add($album, true);
                }
                $progressBar->advance();
            }
        } catch (Exception $ex) {
            $io->error($ex->getMessage());
            return Command::FAILURE;
        }

        $io->success('Sucessfully saved to database');

        return Command::SUCCESS;
    }

    private function searchArtists($searchParam)
    {
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

        return $response->getContent();
    }

    private function searchAlbums($spotifyArtist)
    {
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

        return $responseAlbums->getContent();
    }

    private function makeArtist($spotifyArtist)
    {
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

        return $artist;
    }

    private function makeAlbum($spotifyAlbum, $artist)
    {
        $album = new Album();
        $album->setSpotifyAlbumId($spotifyAlbum->id);;
        $album->setName($spotifyAlbum->name);
        $album->setReleaseDate($spotifyAlbum->release_date);
        $album->setTotalTracks($spotifyAlbum->total_tracks);
        $album->setArtist($artist);

        return $album;
    }
}
