<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(private readonly TMDBManager $tmdb)
    {
    }

    #[Route('/genres', name: 'api_genres', methods: ['GET'])]
    public function genres(): Response
    {
        return $this->json($this->tmdb->getGenres());
    }

    #[Route('/genres/{genreId<\d+>}/movies', name: 'api_movies', methods: ['GET'])]
    public function movies(int $genreId): Response
    {
        return $this->json($this->tmdb->getMoviesByGenre($genreId));
    }
}
