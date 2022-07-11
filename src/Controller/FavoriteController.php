<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Favorite;
use App\Repository\AlbumRepository;
use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("users/favorite", name="app_favorite")
     */
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $favorites = $favoriteRepository->findAll();
        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites
        ]);
    }

    /**
     * @Route("users/favorite/{id}", name="app_favorite_album")
     */
    public function favorite($id, AlbumRepository $albumRepository, FavoriteRepository $favoriteRepository): Response
    {
        $album = $albumRepository->findOneByAlbumId($id);

        $favorite = $favoriteRepository->findOneByUserAndAlbumId($this->getUser()->getId(), $album->getId());

        if ($favorite == null) {
            $favoriteObject = new Favorite();
            $favoriteObject->setUser($this->getUser());
            $favoriteObject->setAlbum($album);

            $favoriteRepository->add($favoriteObject, true);
        }

        return $this->redirectToRoute('app_artist_show', ['id' => $album->getArtist()->getId()]);
    }
    /**
     * @Route("users/unfavorite/{id}", name="app_unfavorite_album")
     */
    public function unfavorite(Favorite $favorite, FavoriteRepository $favoriteRepository): Response
    {

        $favoriteRepository->remove($favorite, true);

        return $this->redirectToRoute('app_favorite');
    }
}
