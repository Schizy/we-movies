<?php

namespace App\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\DTO\Movie;
use App\TMDB\Mapper\DTOMapper;

class TMDBManager
{
    public function __construct(
        private readonly TMDBClient $tmdbClient,
        private readonly DTOMapper  $mapper,
    )
    {
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return $this->mapper->map($this->tmdbClient->getGenres(), Genre::class);
    }

    public function getMoviesByGenre(int $genreId): array
    {
        return $this->mapper->map($this->tmdbClient->getMoviesByGenre($genreId), Movie::class);
    }
}
