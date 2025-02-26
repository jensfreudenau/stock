<?php

namespace App\DTOs\Mappers;

use App\DTOs\GeneralDTO;
use App\DTOs\UserDTO;
use App\DTOs\CustomerDTO;

class DTOMapper
{
    public static function mapToGeneralDTO(object $dto): GeneralDTO
    {
        return match (get_class($dto)) {
            UserDTO::class => new GeneralDTO(
                id: (string) $dto->userId,
                name: $dto->fullName,
                email: $dto->contactEmail
            ),
            CustomerDTO::class => new GeneralDTO(
                id: $dto->customerNumber,
                name: $dto->companyName,
                email: $dto->businessEmail
            ),
            default => throw new \InvalidArgumentException("Unknown DTO type")
        };
    }
}

