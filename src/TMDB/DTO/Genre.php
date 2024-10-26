<?php

namespace App\TMDB\DTO;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class Genre
{
    #[NotBlank]
    #[GreaterThan(0)]
    public ?int $id;

    #[NotBlank]
    public ?string $name;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
    }
}
