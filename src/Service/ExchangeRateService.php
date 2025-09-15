<?php declare(strict_types = 1);

namespace App\Service;

use App\DTO\ExchangeRateRequestDTO;
use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ExchangeRateService
{
    private const string DATETIME_MODIFY = '-24 hours';

    public function __construct(
        private readonly BinanceApiClient $binanceApiClient,
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function updateRates(): void
    {
        $rates = $this->binanceApiClient->fetchExchangeRates();

        if (empty($rates)) {
            $this->logger->warning('No exchange rates received from Binance API');

            return;
        }

        foreach ($rates as $currencyPair => $rate) {
            $exchangeRate = new ExchangeRate();
            $exchangeRate->setCurrencyPair($currencyPair);
            $exchangeRate->setRate($rate);

            $this->entityManager->persist($exchangeRate);
        }

        $this->entityManager->flush();
    }

    public function getRatesForLast24Hours(ExchangeRateRequestDTO $exchangeRateRequestDTO): array
    {
        $since = new \DateTimeImmutable(self::DATETIME_MODIFY);

        return $this->exchangeRateRepository->findRatesForLast24Hours($exchangeRateRequestDTO->pair, $since);
    }

    public function getRatesForDay(ExchangeRateRequestDTO $exchangeRateRequestDTO): array
    {
        $date = new \DateTimeImmutable($exchangeRateRequestDTO->date);

        return $this->exchangeRateRepository->findRatesForDay($exchangeRateRequestDTO->pair, $date);
    }
}
