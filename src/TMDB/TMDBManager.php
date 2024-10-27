<?php

namespace App\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\DTO\Movie;
use App\TMDB\DTO\Video;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TMDBManager
{
    public function __construct(
        private readonly TMDBClient     $tmdbClient,
        private readonly CacheInterface $cache,
        private readonly DTOMapper      $mapper,
    )
    {
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        $genres = $this->cache('getGenres', function (): array {
            return $this->tmdbClient->getGenres();
        });

        return $this->mapper->map($genres, Genre::class);
    }

    /**
     * @return Movie[]
     */
    public function getMoviesByGenre(int $genreId): array
    {
        $moviesByGenre = $this->cache('getMoviesByGenre' . $genreId, function () use ($genreId): array {
            return $this->tmdbClient->getMoviesByGenre($genreId);
        });

        return $this->mapper->map($moviesByGenre, Movie::class);
    }

    /**
     * @return Movie[]
     */
    public function searchMovies(string $term): array
    {
        $searchMovies = $this->cache('searchMovies' . $term, function () use ($term): array {
            return $this->tmdbClient->searchMovies($term);
        });

        return $this->mapper->map($searchMovies, Movie::class);
    }

    public function mostPopular(): Movie
    {
        $mostPopular = $this->cache('mostPopular', function (): array {
            return $this->tmdbClient->mostPopular();
        });

        return $this->mapper->mapOne($mostPopular, Movie::class);
    }

    /**
     * @return Video[]
     */
    public function videosByMovieId(int $movieId): array
    {
        $videos = $this->cache('videos' . $movieId, function () use ($movieId): array {
            return $this->tmdbClient->videosByMovieId($movieId);
        });

        return $this->mapper->map($videos, Video::class);
    }

    public function movieById(int $movieId)
    {
        $movieById = $this->cache('movieById' . $movieId, function () use ($movieId): array {
            return $this->tmdbClient->movieById($movieId);
        });

        return $this->mapper->mapOne($movieById, Movie::class);
    }

    private function cache(string $key, callable $clientCall): array
    {
        return $this->cache->get($key, function (ItemInterface $item) use ($clientCall): array {
            $item->expiresAfter(3600);

            return $clientCall();
        });
    }
}
