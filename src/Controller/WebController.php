<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(TMDBManager $tmdb): Response
    {
        $genres = $tmdb->getGenres();
        $mostPopular = $tmdb->mostPopular();
        $videos = $tmdb->videosByMovieId($mostPopular->id);
        return $this->render('web/home.html.twig', [
            'genres' => $genres,
            'mostPopular' => $mostPopular,
            'videos' => $videos,
        ]);
    }

    #[Route('/genre/{genreId<\d+>}', name: 'app_genre')]
    public function genre(TMDBManager $tmdb): Response
    {
        $genres = $tmdb->getGenres();
        $mostPopular = $tmdb->mostPopular();
        $videos = $tmdb->videosByMovieId($mostPopular->id);
        return $this->render('web/genre.html.twig', [
            'genres' => $genres,
            'mostPopular' => $mostPopular,
            'videos' => $videos,
        ]);
    }
}
