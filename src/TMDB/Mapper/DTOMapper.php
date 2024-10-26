<?php

namespace App\TMDB\Mapper;

use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOMapper
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface    $logger)
    {
    }

    public function map(array $data, string $dtoClass): array
    {
        $dtos = [];
        foreach ($data as $dtoData) {
            $dto = new $dtoClass($dtoData);

            $this->validateOrThrowException($dto, $dtoData, $dtoClass);

            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * @throws \Exception
     */
    protected function validateOrThrowException(object $dto, array $data, string $dtoClass): void
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $this->logger->error('Impossible to map ' . $dtoClass . ' with invalid data', ['data' => $data]);
            throw new \Exception('Invalid data, impossible to map DTO ' . $dtoClass);
        }
    }
}
