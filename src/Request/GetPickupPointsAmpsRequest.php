<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Request;

readonly class GetPickupPointsAmpsRequest implements RequestInterface
{
    private const ENDPOINT = '/api/v1/apms:customerSearch';
    private const HTTP_METHOD = 'GET';

    public function __construct(
        private string $token,
        private string $region,
    ) {
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrlQuery(): ?string
    {
        return 'regionLanguageTag=' . $this->region;
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->getToken()}",
        ];
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
