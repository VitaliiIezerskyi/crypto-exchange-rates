<?php declare(strict_types = 1);

namespace App\DTO;

readonly class ExchangeRateDTO
{
    public function __construct(
        public string $currencyPair,
        public string $rate,
        public int $createdAt,
    ) {
    }
}
