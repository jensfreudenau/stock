<?php

namespace App\DTOs;

class GeneralDTO
{
    public function __construct(
        public string $stockDate,
        public int $close,
        public ?int $portfolioId = null,
        public ?string $symbol = null,
        public ?int $volume = null,
        public ?string $isin = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            stockDate: $data['stock_date'],
            close: $data['close'],
            portfolioId: $data['portfolio_id'] ?? null,
            symbol: $data['symbol'] ?? '',
            volume: $data['volume'] ?? null,
            isin: $data['isin'] ?? null
        );
    }
}
