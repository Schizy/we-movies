<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TMDBManager $tmdb): Response
    {
        $genres = $tmdb->getGenres();
        $mostPopular = $tmdb->mostPopular();
        $videos = $tmdb->videosByMovieId($mostPopular->id);
        return $this->render('home/index.html.twig', [
            'genres' => $genres,
            'mostPopular' => $mostPopular,
            'videos' => $videos,
        ]);
    }
}
