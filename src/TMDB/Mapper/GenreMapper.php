<?php

namespace App\TMDB\Mapper;

use App\TMDB\DTO\Genre;
use Psr\Log\LoggerInterface;

class GenreMapper
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(array $data): array
    {
        $genres = [];
        foreach ($data as $genre) {
            if (!isset($genre['id'], $genre['name'])) {
                $this->logger->error('Impossible to map GENRE with invalid data', ['genre' => $genre]);
                throw new \Exception('Invalid "GENRE" data, impossible to map DTO');
            }

            $genres[] = new Genre($genre['id'], $genre['name']);
        }

        return $genres;
    }
}
