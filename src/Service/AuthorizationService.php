<?php

namespace Answear\BoxNowBundle\Service;

use Answear\BoxNowBundle\Client\Client;
use Answear\BoxNowBundle\ConfigProvider;
use Answear\BoxNowBundle\Request\AuthorizeRequest;
use Answear\BoxNowBundle\Response\AuthorizationResponse;
use Answear\BoxNowBundle\Serializer\Serializer;

readonly class AuthorizationService
{
    public function __construct(
        private ConfigProvider $configProvider,
        private Serializer $serializer,
        private Client $client,
    ) {
    }

    public function authorize(): AuthorizationResponse
    {
        $response = $this->client->request(
            new AuthorizeRequest(
                $this->configProvider->clientId,
                $this->configProvider->clientSecret
            )
        );

        return AuthorizationResponse::fromArray(
            $this->serializer->decodeResponse($response)
        );
    }
}
