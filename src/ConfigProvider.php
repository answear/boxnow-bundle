<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle;

class ConfigProvider
{
    public const API_URL = 'https://api-stage.boxnow.gr';

    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $apiUrl = self::API_URL,
    ) {
    }
}
