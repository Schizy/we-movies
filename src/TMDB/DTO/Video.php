<?php

namespace App\TMDB\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;

class Video
{
    #[NotBlank]
    public ?string $name;

    #[NotBlank]
    public ?string $youtubeKey;

    #[NotBlank]
    public ?string $type;

    public bool $official = false;

    #[NotBlank]
    public ?\DateTimeImmutable $publishedAt;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->youtubeKey = isset($data['key'], $data['site']) && $data['site'] === 'YouTube' ? $data['key'] : null;
        $this->type = $data['type'] ?? null;
        $this->official = isset($data['official']) && $data['official'] === true;
        $this->publishedAt = isset($data['published_at']) ? new \DateTimeImmutable($data['published_at']) : null;
    }
}
