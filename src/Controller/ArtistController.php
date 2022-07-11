<?php

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ArtistController extends AbstractController
{
    /**
     * @Route("/user/artist", name="app_artist")
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $dql = 'SELECT a FROM App\Entity\Artist a';
        $query = $entityManager->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('artist/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/user/artist/{id}", name="app_artist_show", methods={"GET"})
     */
    public function show(Artist $artist): Response
    {
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }
}
