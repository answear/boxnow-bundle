<?php

namespace Answear\BoxNowBundle\Client;

use Answear\BoxNowBundle\Exception\RequestException;
use Answear\BoxNowBundle\Logger\BoxNowLogger;
use Answear\BoxNowBundle\Request\RequestInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const CONNECTION_TIMEOUT = 10;
    private const TIMEOUT = 30;
    
    public function __construct(
        private readonly RequestTransformerInterface $requestTransformer,
        private readonly BoxNowLogger $logger,
        private ?ClientInterface $client = null,
    ) {
        $this->client ??= new GuzzleClient(['timeout' => self::TIMEOUT, 'connect_timeout' => self::CONNECTION_TIMEOUT]);
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $this->logger->setRequestId(uniqid('BOXNOW-', more_entropy: true));

        try {
            $psrRequest = $this->requestTransformer->transform($request);
            $this->logger->logRequest($request->getEndpoint(), $psrRequest);

            $response = $this->client->send($psrRequest);

            $this->logger->logResponse($request->getEndpoint(), $psrRequest, $response);

            if ($response->getBody()->isSeekable()) {
                $response->getBody()->rewind();
            }
        } catch (GuzzleException $exception) {
            $this->logger->logError($request->getEndpoint(), $exception);

            throw new RequestException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (\Throwable $throwable) {
            $this->logger->logError($request->getEndpoint(), $throwable);

            throw $throwable;
        } finally {
            $this->logger->clearRequestId();
        }

        return $response;
    }
}
