<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Response;

use Webmozart\Assert\Assert;

class AuthorizationResponse implements ResponseInterface
{
    private string $accessToken;

    private string $tokenType;

    private int $expiresIn;

    public function __construct(private readonly array $data)
    {
        $this->handleData();
    }

    public function handleData(): void
    {
        Assert::string($this->data['access_token'] ?? null);
        Assert::string($this->data['token_type'] ?? null);
        Assert::integer($this->data['expires_in'] ?? null);

        $this->setAccessToken($this->data['access_token']);
        $this->setTokenType($this->data['token_type']);
        $this->setExpiresIn($this->data['expires_in']);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }
}
