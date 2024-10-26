<?php

namespace App\TMDB;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBClient
{
    public const GENRE_LIST = '/genre/movie/list';

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
            throw new \Exception('Invalid TMDB API data for ' . self::GENRE_LIST);
        }

        return $data['genres'];
    }

    private function get(string $url): array
    {
        $url = $this->tmdbApiUrl . $url;
        $response = $this->httpClient->request('GET', $url, ['query' => ['api_key' => $this->tmdbApiKey]]);

        if ($response->getStatusCode() !== 200) {
            $this->logger->error('TMDB API {url} error: ' . $response->getContent(), ['url' => $url]);
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
}
