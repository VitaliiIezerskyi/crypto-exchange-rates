<?php

declare(strict_types = 1);

namespace App\EventListener;

use App\Service\Response\ResponseService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(
        private ResponseService $apiResponseService,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $event->setResponse($this->apiResponseService->answerException($exception));
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['', -32]];
    }
}
