<?php

namespace App\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\Mapper\GenreMapper;

class TMDBManager
{
    public function __construct(
        private readonly TMDBClient  $tmdbClient,
        private readonly GenreMapper $genreMapper,
    )
    {
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return ($this->genreMapper)($this->tmdbClient->getGenres());
    }
}
