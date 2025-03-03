<?php

namespace App\DTOs;

class IngDTO
{
    public function __construct(
        public string $item0,
        public string $item1,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            item0: $data['item_0'],
            item1: $data['item_1']
        );
    }
}

