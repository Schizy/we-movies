<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/movies')]
class MovieController extends AbstractController
{
    public function __construct(private readonly TMDBManager $tmdb)
    {
    }

    #[Route('/search/{term}', name: 'api_search_movies', methods: ['GET'])]
    public function searchMovies(string $term): Response
    {
        return $this->json($this->tmdb->searchMovies($term));
    }

    #[Route('/most-popular', name: 'api_most_popular_movie', methods: ['GET'])]
    public function mostPopular(): Response
    {
        return $this->json($this->tmdb->mostPopular());
    }
}
