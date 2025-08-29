<?php

namespace Answear\BoxNowBundle\Service;

use Answear\BoxNowBundle\Client\Client;
use Answear\BoxNowBundle\DTO\PickupPointDTO;
use Answear\BoxNowBundle\Enum\RegionEnum;
use Answear\BoxNowBundle\Request\GetPickupPointsAmpsRequest;
use Answear\BoxNowBundle\Request\GetPickupPointsByRegionRequest;
use Answear\BoxNowBundle\Request\GetPickupPointsRequest;
use Answear\BoxNowBundle\Response\GetPickupPointsAmpsResponse;
use Answear\BoxNowBundle\Response\GetPickupPointsResponse;
use Answear\BoxNowBundle\Serializer\Serializer;

readonly class PickupPointService
{
    public function __construct(
        private Client $client,
        private Serializer $serializer,
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

    /**
     * @return PickupPointDTO[]
     */
    public function getAllWithRegion(string $region): array
    {
        $response = $this->client->request(new GetPickupPointsAmpsRequest($region));

        $pickupPointsResponse = GetPickupPointsAmpsResponse::fromArray(
            $this->serializer->decodeResponse($response)
        );

        return $pickupPointsResponse->getPickupPoints();
    }

    /**
     * @return PickupPointDTO[]
     */
    public function getAllByRegion(RegionEnum $region): array
    {
        $response = $this->client->request(new GetPickupPointsByRegionRequest($region));

        $pickupPointsResponse = GetPickupPointsResponse::fromArray(
            $this->serializer->decodeResponse($response)
        );

        return $pickupPointsResponse->getPickupPoints();
    }
}
