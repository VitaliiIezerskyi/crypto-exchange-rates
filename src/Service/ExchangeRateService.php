<?php declare(strict_types = 1);

namespace App\Service;

use App\DTO\ExchangeRateRequestDTO;
use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Psr\Log\LoggerInterface;

class ExchangeRateService
{
    private const string DATETIME_MODIFY = '-24 hours';

    public function __construct(
        private readonly BinanceApiClient $binanceApiClient,
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function updateRates(): void
    {
        $this->logger->info('Starting exchange rate update');
        $rates = $this->binanceApiClient->fetchExchangeRates();

        if (empty($rates)) {
            $this->logger->warning('No exchange rates received from Binance API');

            return;
        }

        foreach ($rates as $currencyPair => $rate) {
            try {
                $exchangeRate = new ExchangeRate();
                $exchangeRate->setCurrencyPair($currencyPair);
                $exchangeRate->setRate($rate);

                $this->exchangeRateRepository->save($exchangeRate);

                $this->logger->debug('Saved exchange rate', [
                    'pair' => $currencyPair,
                    'rate' => $rate,
                ]);
            } catch (\Throwable $exception) {
                $this->logger->error('Failed to save exchange rate', [
                    'pair' => $currencyPair,
                    'rate' => $rate,
                    'exception' => $exception,
                ]);
            }
        }
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
