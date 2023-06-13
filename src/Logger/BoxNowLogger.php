<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Logger;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class BoxNowLogger
{
    private const MESSAGE_PREFIX = '[BOXNOW] ';
    private const INVALID_JSON = '--- INVALID JSON ---';
    private const MAX_CONTENT_LENGTH = 3000;
    private const HUGE_CONTENT_SKIPPED = '--- HUGE CONTENT SKIPPED ---';

    private ?string $requestId = null;

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function logRequest(string $endpoint, RequestInterface $request): void
    {
        try {
            $this->logger->info(
                $this->decorateMessage('Request', $endpoint),
                [
                    'requestId' => $this->requestId,
                    'endpoint' => $endpoint,
                    'uri' => $this->getUri($request->getUri()),
                    'body' => $this->getParsedContent($request->getBody()->getContents()),
                ]
            );
        } catch (\Throwable $throwable) {
            $this->logger->error(
                $this->decorateMessage('Cannot log request', $endpoint),
                ['exception' => $this->decorateError($throwable)]
            );
        }
    }

    public function logResponse(string $endpoint, RequestInterface $request, ResponseInterface $response): void
    {
        try {
            $this->logger->info(
                $this->decorateMessage('Response', $endpoint),
                [
                    'endpoint' => $endpoint,
                    'requestId' => $this->requestId,
                    'uri' => $this->getUri($request->getUri()),
                    'response' => $this->getParsedContent($response->getBody()->getContents()),
                ]
            );
        } catch (\Throwable $throwable) {
            $this->logger->error(
                $this->decorateMessage('Cannot log response', $endpoint),
                ['exception' => $this->decorateError($throwable)]
            );
        }
    }

    public function logError(string $endpoint, \Throwable $throwable): void
    {
        $this->logger->error(
            $this->decorateMessage('Exception', $endpoint),
            [
                'endpoint' => $endpoint,
                'requestId' => $this->requestId,
                'exception' => $this->decorateError($throwable),
            ]
        );
    }

    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function clearRequestId(): void
    {
        $this->requestId = null;
    }

    private function decorateMessage(string $message, string $endpoint): string
    {
        return self::MESSAGE_PREFIX . $message . ' - ' . $endpoint;
    }

    private function decorateError(\Throwable $error): array
    {
        return [
            'message' => $error->getMessage(),
            'code' => $error->getCode(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
        ];
    }

    private function getParsedContent(string $content): array|string|null
    {
        if (empty($content)) {
            return null;
        }

        if (mb_strlen($content) > self::MAX_CONTENT_LENGTH) {
            return self::HUGE_CONTENT_SKIPPED;
        }

        try {
            return json_decode(json: $content, associative: true, depth: 512, flags: JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return self::INVALID_JSON;
        }
    }

    private function getUri(UriInterface $uri): array
    {
        parse_str($uri->getQuery(), $query);

        return [
            'path' => $uri->getPath(),
            'query' => $query,
        ];
    }
}
