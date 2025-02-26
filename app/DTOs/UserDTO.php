<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public int $userId,
        public string $fullName,
        public string $contactEmail
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            fullName: $data['full_name'],
            contactEmail: $data['email']
        );
    }
}

