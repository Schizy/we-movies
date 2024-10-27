<?php

namespace App\TMDB;

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
            try {
                $dtos[] = $this->mapOne($dtoData, $dtoClass);
            } catch (\Exception $e) {
                continue;
            }
        }

        return $dtos;
    }

    public function mapOne(array $data, string $dtoClass): object
    {
        $dto = new $dtoClass($data);

        $this->validateOrThrowException($dto, $data, $dtoClass);

        return $dto;
    }

    /**
     * @throws \Exception
     */
    protected function validateOrThrowException(object $dto, array $data, string $dtoClass): void
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $this->logger->error('Impossible to map ' . $dtoClass . ' with invalid data', ['data' => $data, 'violations' => $violations]);
            throw new \Exception('Invalid data, impossible to map DTO ' . $dtoClass);
        }
    }
}
