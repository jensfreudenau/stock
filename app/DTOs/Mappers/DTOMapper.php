<?php

namespace App\DTOs\Mappers;

use App\DTOs\EtfDTO;
use App\DTOs\GeneralDTO;

use App\DTOs\IngDTO;

class DTOMapper
{
    public static function mapToGeneralDTO(object $dto, $portfolio): GeneralDTO
    {
        return match (get_class($dto)) {
            EtfDTO::class => new GeneralDTO(
                stockDate: $dto->item0,
                close: $dto->item1,
                portfolioId:  $portfolio->id,
                symbol: $portfolio->symbol,
                isin: $portfolio->isin,
            ),
            IngDTO::class => new GeneralDTO(
                stockDate: $dto->item0,
                close: $dto->item1,
                portfolioId:  $portfolio->id,
                symbol: $portfolio->symbol,
                isin: $portfolio->isin,
            ),
            default => throw new \InvalidArgumentException("Unknown DTO type")
        };
    }
}

