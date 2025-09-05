<?php declare(strict_types = 1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BinanceApiClient
{
    private const string BINANCE_API_URL = 'https://api.binance.com/api/v3/ticker/price';
    private const string BTC_EUR_PAIR = 'BTCEUR';
    private const string ETH_EUR_PAIR = 'ETHEUR';
    private const string LTC_EUR_PAIR = 'LTCEUR';
    private const array SUPPORTED_PAIRS = [
        self::BTC_EUR_PAIR,
        self::ETH_EUR_PAIR,
        self::LTC_EUR_PAIR,
    ];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @return array<string, string> Currency pair => rate
     */
    public function fetchExchangeRates(): array
    {
        $rates = [];

        foreach (self::SUPPORTED_PAIRS as $pair) {
            try {
                $rate = $this->fetchRate($pair);

                if ($rate !== null) {
                    $formattedPair = $this->formatCurrencyPair($pair);
                    $rates[$formattedPair] = $rate;
                }
            } catch (\Throwable $exception) {
                $this->logger->error('Failed to fetch rate for pair: '.$pair, [
                    'exception' => $exception,
                    'pair' => $pair,
                ]);
            }
        }

        return $rates;
    }

    private function fetchRate(string $symbol): ?string
    {
        try {
            $response = $this->httpClient->request('GET', self::BINANCE_API_URL, [
                'query' => ['symbol' => $symbol],
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->logger->error('Binance API returned non-200 status', [
                    'status_code' => $response->getStatusCode(),
                    'symbol' => $symbol,
                ]);

                return null;
            }

            $data = $response->toArray();

            if (!isset($data['price'])) {
                $this->logger->error('Invalid response from Binance API', [
                    'response' => $data,
                    'symbol' => $symbol,
                ]);

                return null;
            }

            return $data['price'];
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Transport exception when fetching from Binance API', [
                'error' => $e->getMessage(),
                'symbol' => $symbol,
            ]);

            return null;
        }
    }

    private function formatCurrencyPair(string $binancePair): string
    {
        return match ($binancePair) {
            self::BTC_EUR_PAIR => 'EUR/BTC',
            self::ETH_EUR_PAIR => 'EUR/ETH',
            self::LTC_EUR_PAIR => 'EUR/LTC',
            default => $binancePair,
        };
    }
}
