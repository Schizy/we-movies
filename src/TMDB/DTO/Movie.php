<?php

namespace App\TMDB\DTO;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class Movie
{
    #[NotBlank]
    #[GreaterThan(0)]
    public ?int $id;

    #[NotBlank]
    public ?string $image;

    #[NotBlank]
    public ?string $title;

    #[NotBlank]
    public ?string $description;

    #[NotBlank]
    public ?string $releaseDate;

    #[NotBlank]
    public ?float $voteAverage;

    #[NotBlank]
    public ?float $voteCount;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->description = $data['overview'] ?? null;
        $this->image = $data['poster_path'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->releaseDate = $data['release_date'] ?? null;
        $this->voteAverage = $data['vote_average'] ?? null;
        $this->voteCount = $data['vote_count'] ?? null;
    }
}
