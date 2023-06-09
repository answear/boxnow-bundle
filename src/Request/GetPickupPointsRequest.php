<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Request;

class GetPickupPointsRequest implements RequestInterface
{
    private const ENDPOINT = '/api/v1/destinations';
    private const HTTP_METHOD = 'GET';

    public function __construct(private readonly string $token)
    {
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
        return null;
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
