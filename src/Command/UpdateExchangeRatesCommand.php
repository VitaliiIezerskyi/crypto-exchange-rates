<?php declare(strict_types = 1);

namespace App\Command;

use App\Service\ExchangeRateService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:update-exchange-rates', description: 'Update exchange rates from Binance API')]
class UpdateExchangeRatesCommand extends Command
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting exchange rates update from Binance API...');

        try {
            $this->exchangeRateService->updateRates();
            $io->success('Exchange rates updated successfully');

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to update exchange rates: '.$exception->getMessage(), ['exception' => $exception]);
            $io->error('Failed to update exchange rates: '.$exception->getMessage());

            return Command::FAILURE;
        }
    }
}
