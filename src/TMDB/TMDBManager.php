<?php

namespace App\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\DTO\Movie;
use App\TMDB\DTO\Video;

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

    /**
     * @return Movie[]
     */
    public function getMoviesByGenre(int $genreId): array
    {
        return $this->mapper->map($this->tmdbClient->getMoviesByGenre($genreId), Movie::class);
    }

    /**
     * @return Movie[]
     */
    public function searchMovies(string $term): array
    {
        return $this->mapper->map($this->tmdbClient->searchMovies($term), Movie::class);
    }

    public function mostPopular(): Movie
    {
        return $this->mapper->mapOne($this->tmdbClient->mostPopular(), Movie::class);
    }

    /**
     * @return Video[]
     */
    public function videosByMovieId(int $movieId): array
    {
        return $this->mapper->map($this->tmdbClient->videosByMovieId($movieId), Video::class);
    }

    public function movieById(int $movieId)
    {
        return $this->mapper->mapOne($this->tmdbClient->movieById($movieId), Movie::class);
    }
}
