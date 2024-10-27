<?php

namespace App\TMDB;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBClient
{
    public const GENRE_LIST = '/genre/movie/list';
    public const DISCOVER_MOVIES = '/discover/movie';
    public const MOVIE_SEARCH = '/search/movie';
    public const MOVIE_BY_ID = '/movie/%d';
    public const MOVIE_VIDEOS = '/movie/%d/videos';

    public function __construct(
        private readonly string              $tmdbApiUrl,
        private readonly string              $tmdbApiKey,
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface     $logger,
    )
    {
    }

    public function getGenres(): array
    {
        return $this->get(self::GENRE_LIST, mainKey: 'genres');
    }

    public function getMoviesByGenre(int $genreId): array
    {
        return $this->get(self::DISCOVER_MOVIES, ['with_genres' => $genreId]);
    }

    public function searchMovies(string $term): array
    {
        return $this->get(self::MOVIE_SEARCH, ['query' => $term]);
    }

    public function mostPopular(): array
    {
        $data = $this->get(self::DISCOVER_MOVIES, ['sort_by' => 'vote_average.desc', 'vote_count.gte' => 1000]);

        return $data[0];
    }

    public function videosByMovieId(int $movieId): array
    {
        return $this->get(sprintf(self::MOVIE_VIDEOS, $movieId));
    }

    public function movieById(int $movieId): array
    {
        return $this->get(sprintf(self::MOVIE_BY_ID, $movieId), mainKey: null);
    }

    private function get(string $url, array $queryParams = [], $mainKey = 'results'): array
    {
        $url = $this->tmdbApiUrl . $url;
        $response = $this->httpClient->request('GET', $url, [
            'query' => ['api_key' => $this->tmdbApiKey] + $queryParams,
        ]);

        if ($response->getStatusCode() === 404) {
            throw new NotFoundHttpException('Page not found');
        }

        if ($response->getStatusCode() !== 200) {
            $this->logger->error('TMDB API {url} error: ' . $response->getStatusCode(), ['url' => $url]);
            throw new \Exception('TMDB API returned a ' . $response->getStatusCode() . ' status code');
        }

        try {
            $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('TMDB API {url} response is not a valid JSON: ' . $response->getContent(), ['url' => $url]);
            throw new \Exception('TMDB API response is not a valid JSON');
        }

        if ($mainKey && !isset($data[$mainKey])) {
            $this->invalidDataException($url . '?' . http_build_query($queryParams));
        }

        return $mainKey ? $data[$mainKey] : $data;
    }

    private function invalidDataException(string $endpoint): void
    {
        throw new \Exception('Invalid TMDB API data for the endpoint : ' . $endpoint);
    }
}
