<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebController extends AbstractController
{
    public function __construct(private readonly TMDBManager $tmdb)
    {
    }

    #[Route('/', name: 'app_home')]
    #[Route('/movie/{movieId<\d+>}', name: 'app_movie')]
    public function home(int $movieId = null): Response
    {
        $genres = $this->tmdb->getGenres();
        $movie = $movieId ? $this->tmdb->movieById($movieId) : $this->tmdb->mostPopular();
        $videos = $this->tmdb->videosByMovieId($movie->id);

        return $this->render('web/home.html.twig', [
            'genres' => $genres,
            'movie' => $movie,
            'videos' => $videos,
        ]);
    }

    #[Route('/genre/{genreId<\d+>}', name: 'app_genre')]
    public function genre(int $genreId): Response
    {
        $genres = $this->tmdb->getGenres();
        $movies = $this->tmdb->getMoviesByGenre($genreId);

        return $this->render('web/genre.html.twig', [
            'genres' => $genres,
            'movies' => $movies,
        ]);
    }

    #[Route('/movie/modal/{movieId<\d+>}', name: 'app_movie_modal')]
    public function movieModal(int $movieId): Response
    {
        $videos = $this->tmdb->videosByMovieId($movieId);

        return $this->render('web/_movieModal.html.twig', [
            'videos' => $videos,
        ]);
    }
}
