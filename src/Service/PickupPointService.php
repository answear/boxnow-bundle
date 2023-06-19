<?php

namespace Answear\BoxNowBundle\Service;

use Answear\BoxNowBundle\Client\Client;
use Answear\BoxNowBundle\DTO\PickupPointDTO;
use Answear\BoxNowBundle\Request\GetPickupPointsRequest;
use Answear\BoxNowBundle\Response\GetPickupPointsResponse;
use Answear\BoxNowBundle\Serializer\Serializer;

class PickupPointService
{
    public function __construct(
        private readonly Client $client,
        private readonly Serializer $serializer,
    ) {
    }

    /**
     * @return PickupPointDTO[]
     */
    public function getAll(string $token): array
    {
        $response = $this->client->request(new GetPickupPointsRequest($token));

        $pickupPointsResponse = GetPickupPointsResponse::fromArray(
            $this->serializer->decodeResponse($response)
        );

        return $pickupPointsResponse->getPickupPoints();
    }
}
