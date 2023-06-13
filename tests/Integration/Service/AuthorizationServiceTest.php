<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Integration\Service;

use Answear\BoxNowBundle\Exception\RequestException;
use Answear\BoxNowBundle\Response\AuthorizationResponse;
use Answear\BoxNowBundle\Service\AuthorizationService;
use Answear\BoxNowBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthorizationServiceTest extends ServiceTestCase
{
    public function testSuccessfulAuthorization(): void
    {
        $this->setClient(withLogger: true);
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/authorization/success.json')
            )
        );

        $response = $service->authorize();

        $this->assertInstanceOf(AuthorizationResponse::class, $response);

        $this->assertSame('--access-token--', $response->getAccessToken());
        $this->assertSame('Bearer', $response->getTokenType());
        $this->assertSame(3600, $response->getExpiresIn());
    }

    public function testWrongCredentialsAuthorization(): void
    {
        $this->expectException(RequestException::class);

        $this->setClient();
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_UNAUTHORIZED,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/authorization/wrong-credentials.json')
            )
        );

        $service->authorize();
    }

    public function testInvalidBodyAuthorization(): void
    {
        $this->expectException(RequestException::class);
        $this->expectExceptionCode(SymfonyResponse::HTTP_BAD_REQUEST);

        $this->setClient();
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_BAD_REQUEST,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/authorization/invalid-body.json')
            )
        );

        $service->authorize();
    }

    private function getService(): AuthorizationService
    {
        return new AuthorizationService($this->getConfigProvider(), $this->getSerializer(), $this->getClient());
    }

    protected function getLoggerStream(): array
    {
        return [
            '[BOXNOW] Request - /api/v1/auth-sessions' => [
                'endpoint' => '/api/v1/auth-sessions',
                'uri' => [
                    'path' => '/api/v1/auth-sessions',
                    'query' => [],
                ],
                'body' => [
                    'client_id' => 'clientId',
                    'client_secret' => 'clientSecret',
                    'grant_type' => 'client_credentials',
                ],
            ],
            '[BOXNOW] Response - /api/v1/auth-sessions' => [
                'endpoint' => '/api/v1/auth-sessions',
                'uri' => [
                    'path' => '/api/v1/auth-sessions',
                    'query' => [],
                ],
                'response' => [
                    'access_token' => '--access-token--',
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                ],
            ],
        ];
    }
}
