<?php

declare(strict_types = 1);

namespace App\Service\Environment;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;

#[AsRoutingConditionService(alias: 'environmentService')]
class EnvironmentService
{
    public function isProd(): bool
    {
        return $this->getAppEnv() === 'prod';
    }

    private function getAppEnv(): string
    {
        // @codingStandardsIgnoreStart

        return $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev';
    }
}
