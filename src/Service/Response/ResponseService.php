<?php

declare(strict_types = 1);

namespace App\Service\Response;

use App\Enum\ApiErrorMessageEnum;
use App\Service\Environment\EnvironmentService;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ResponseService
{
    public const int LIST_PER_PAGE = 25;

    private ?Request $request;

    public function __construct(
        private ResponseTypeService $responseType,
        private PaginatorInterface $paginator,
        private EnvironmentService $environmentService,
        private TranslatorInterface $translator,
        RequestStack $requestStack,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function answer(mixed $data = [], int $code = Response::HTTP_OK): Response
    {
        $data = [
            'success' => true,
            'data' => $data,
        ];

        return new JsonResponse($data, $code);
    }

    public function answerError(
        string $errorMessage,
        int $code = ResponseTypeService::HTTP_INTERNAL_SERVER_ERROR,
    ): Response {
        $data = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $errorMessage ?: $this->responseType->getError($code),
                'description' => $errorMessage ?: $this->responseType->getDescription($code),
            ],
        ];

        return new JsonResponse($data, $code);
    }

    public function answerException(\Throwable $exception): Response
    {
        $code = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : ResponseTypeService::HTTP_INTERNAL_SERVER_ERROR;

        $errorMessage = ApiErrorMessageEnum::getMessage($code);

        $data = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $this->translator->trans($errorMessage->value),
                'description' => $this->translator->trans($errorMessage->value),
            ],
        ];

        return new JsonResponse($data, $code);
    }

    public function getPaginationData(mixed $target, array $additionalData = [], ?int $limit = null): array
    {
        $limit ??= $this->request->query->getInt('limit', self::LIST_PER_PAGE);

        $pagination = $this->paginator->paginate(
            $target,
            $this->request->query->getInt('page', 1),
            $limit,
            ['distinct' => false, 'wrap-queries' => true]
        );

        $pagination->setItemNumberPerPage($limit);

        /* @noinspection PhpParamsInspection */
        return $this->preparePagination($pagination, $additionalData);
    }

    public function preparePagination(SlidingPagination $pagination, array $additionalData = []): array
    {
        $result = [
            'items' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
            'perPage' => $pagination->getItemNumberPerPage(),
            'total' => $pagination->getTotalItemCount(),
        ];

        if (\count($additionalData) !== 0) {
            $result = \array_merge($result, $additionalData);
        }

        return $result;
    }

    public function answerPagination(mixed $target, array $additionalData = []): Response
    {
        return $this->answer($this->getPaginationData($target, $additionalData), ResponseTypeService::HTTP_OK);
    }
}
