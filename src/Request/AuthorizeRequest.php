<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Request;

class AuthorizeRequest implements RequestInterface
{
    private const ENDPOINT = '/api/v1/auth-sessions';
    private const HTTP_METHOD = 'POST';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $grantType = 'client_credentials',
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
        return null;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
