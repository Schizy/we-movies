<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/genres')]
class GenreController extends AbstractController
{
    public function __construct(private readonly TMDBManager $tmdb)
    {
    }

    #[Route('/', name: 'api_genres', methods: ['GET'])]
    public function list(): Response
    {
        return $this->json($this->tmdb->getGenres());
    }

    #[Route('/{genreId<\d+>}/movies', name: 'api_movies', methods: ['GET'])]
    public function moviesByGenre(int $genreId): Response
    {
        return $this->json($this->tmdb->getMoviesByGenre($genreId));
    }
}
