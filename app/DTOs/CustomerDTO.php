<?php

namespace App\DTOs;

class CustomerDTO
{
    public function __construct(
        public string $customerNumber,
        public string $companyName,
        public string $businessEmail
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerNumber: $data['customer_number'],
            companyName: $data['company_name'],
            businessEmail: $data['business_email']
        );
    }
}

