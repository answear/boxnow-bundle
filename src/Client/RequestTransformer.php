<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Client;

use Answear\BoxNowBundle\ConfigProvider;
use Answear\BoxNowBundle\Request\RequestInterface;
use Answear\BoxNowBundle\Serializer\Serializer;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

class RequestTransformer implements RequestTransformerInterface
{
    public function __construct(
        private readonly Serializer $serializer,
        private readonly ConfigProvider $configProvider,
    ) {
    }

    public function transform(RequestInterface $request): PsrRequestInterface
    {
        $url = $this->configProvider->getApiUrl() . $request->getEndpoint();

        if (!is_null($request->getUrlQuery())) {
            $url .= '&' . $request->getUrlQuery();
        }

        $body = 'GET' === $request->getMethod() ? null : $this->serializer->serialize($request);

        return new HttpRequest(
            $request->getMethod(),
            new Uri($url),
            ['Content-type' => 'application/json'] + $request->getHeaders(),
            $body
        );
    }
}
