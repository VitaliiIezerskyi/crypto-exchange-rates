<?php declare(strict_types = 1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExchangeRateRequestDTO
{
    private const array VALID_PAIRS = ['EUR/BTC', 'EUR/ETH', 'EUR/LTC'];

    #[Assert\NotBlank(message: 'Currency pair is required')]
    #[Assert\Choice(choices: self::VALID_PAIRS, message: 'Invalid currency pair. Supported pairs: {{ choices }}')]
    public string $pair;

    #[Assert\NotBlank(message: 'Date is required', groups: ['day'])]
    #[Assert\Date(message: 'Invalid date format. Use YYYY-MM-DD')]
    public ?string $date = null;
}
