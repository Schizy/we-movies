<?php

namespace App\TMDB\DTO;

class Genre
{
    public function __construct(
        public int    $id,
        public string $name,
    )
    {
    }
}
