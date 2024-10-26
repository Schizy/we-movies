<?php

namespace App\Controller;

use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/genres', name: 'api_genres', methods: ['GET'])]
    public function genres(TMDBManager $tmdb): Response
    {
        return $this->json($tmdb->getGenres());
    }
}
