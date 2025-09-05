<?php declare(strict_types = 1);

namespace App\Representation;

use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;

class ExchangeRateListRepresentation
{
    /**
     * @param array<ExchangeRate> $exchangeRates
     *
     * @return array<ExchangeRateDTO>
     */
    public function build(array $exchangeRates): array
    {
        $result = [];

        foreach ($exchangeRates as $exchangeRate) {
            $result[] = new ExchangeRateDTO(
                currencyPair: $exchangeRate->getCurrencyPair(),
                rate: $exchangeRate->getRate(),
                createdAt: $exchangeRate->getCreatedAt()->getTimestamp()
            );
        }

        return $result;
    }
}
