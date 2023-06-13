<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle;

class ConfigProvider
{
    public const API_URL = 'https://api-stage.boxnow.gr';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $apiUrl = self::API_URL,
    ) {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }
}
