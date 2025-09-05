<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\ExchangeRateRequestDTO;
use App\Representation\ExchangeRateListRepresentation;
use App\Service\ExchangeRateService;
use App\Service\Response\ResponseService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rates', name: 'api_rates_')]
class ExchangeRateController extends AbstractController
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService,
        private readonly ResponseService $responseService,
        private readonly ExchangeRateListRepresentation $exchangeRateListRepresentation,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/last-24h', name: 'last_24h', methods: ['GET'])]
    public function getLast24Hours(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY)]
        ExchangeRateRequestDTO $request
    ): Response {
        try {
            $rates = $this->exchangeRateService->getRatesForLast24Hours($request);

            return $this->responseService->answerPagination($this->exchangeRateListRepresentation->build($rates));
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return $this->responseService->answerError('Failed to retrieve exchange rates', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/day', name: 'day', methods: ['GET'])]
    public function getDay(
        #[MapQueryString(validationGroups: ['Default', 'day'])]
        ExchangeRateRequestDTO $request
    ): Response {
        try {
            $rates = $this->exchangeRateService->getRatesForDay($request);

            return $this->responseService->answerPagination($this->exchangeRateListRepresentation->build($rates));
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return $this->responseService->answerError('Failed to retrieve exchange rates', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
