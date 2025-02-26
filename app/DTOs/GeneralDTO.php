<?php

namespace App\DTOs;

class GeneralDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public ?string $phone = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? null
        );
    }
}
