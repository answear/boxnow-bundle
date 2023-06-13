<?php

namespace Answear\BoxNowBundle\Service;

use Answear\BoxNowBundle\Client\Client;
use Answear\BoxNowBundle\ConfigProvider;
use Answear\BoxNowBundle\Request\AuthorizeRequest;
use Answear\BoxNowBundle\Response\AuthorizationResponse;
use Answear\BoxNowBundle\Serializer\Serializer;

class AuthorizationService
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly Serializer $serializer,
        private readonly Client $client,
    ) {
    }

    public function authorize(): AuthorizationResponse
    {
        $response = $this->client->request(
            new AuthorizeRequest(
                $this->configProvider->getClientId(),
                $this->configProvider->getClientSecret()
            )
        );

        return AuthorizationResponse::fromArray(
            $this->serializer->decodeResponse($response)
        );
    }
}
