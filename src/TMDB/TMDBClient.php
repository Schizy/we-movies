<?php

namespace App\TMDB;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBClient
{
    public const GENRE_LIST = '/genre/movie/list';
    public const GENRE_MOVIES = '/discover/movie';

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
        $data = $this->get(self::GENRE_LIST);

        if (!isset($data['genres'])) {
            $this->invalidDataException(self::GENRE_LIST);
        }

        return $data['genres'];
    }

    public function getMoviesByGenre(int $genreId)
    {
        $data = $this->get(self::GENRE_MOVIES, ['with_genres' => $genreId]);

        if (!isset($data['results'])) {
            $this->invalidDataException(self::GENRE_MOVIES);
        }

        return $data['results'];
    }

    private function get(string $url, array $queryParams = []): array
    {
        $url = $this->tmdbApiUrl . $url;
        $response = $this->httpClient->request('GET', $url, [
            'query' => ['api_key' => $this->tmdbApiKey] + $queryParams,
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->logger->error('TMDB API {url} error: ' . $response->getStatusCode(), ['url' => $url]);
            throw new \Exception('TMDB API returned an ' . $response->getStatusCode() . ' status code');
        }

        try {
            $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('TMDB API {url} response is not a valid JSON: ' . $response->getContent(), ['url' => $url]);
            throw new \Exception('TMDB API response is not a valid JSON');
        }

        return $data;
    }

    private function invalidDataException(string $endpoint): void
    {
        throw new \Exception('Invalid TMDB API data for the endpoint : ' . $endpoint);
    }
}
