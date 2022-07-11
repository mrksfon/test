<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/album")
 */
class AdminAlbumController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('admin_album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_album_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlbumRepository $albumRepository, ArtistRepository $artistRepository): Response
    {
        $album = new Album();
        $artists = $artistRepository->findAll();
        $form = $this->createForm(AlbumType::class, $album, [
            'artists' => $artists
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        return $this->render('admin_album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_album_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository, ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();

        $form = $this->createForm(AlbumType::class, $album, [
            'artists' => $artists
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_album_delete", methods={"POST"})
     */
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
