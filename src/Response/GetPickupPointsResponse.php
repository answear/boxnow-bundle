<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Response;

use Answear\BoxNowBundle\DTO\PickupPointDTO;
use Webmozart\Assert\Assert;

class GetPickupPointsResponse implements ResponseInterface
{
    /** @var PickupPointDTO[] */
    private array $pickupPoints = [];

    public function __construct(array $data)
    {
        $this->handleData($data);
    }

    public function handleData(array $data): void
    {
        $this->validatePickupPoints($data['data'] ?? []);

        foreach ($data['data'] as $pickupPoint) {
            $this->pickupPoints[] = new PickupPointDTO(
                $pickupPoint['id'],
                $pickupPoint['type'],
                $pickupPoint['name'],
                $pickupPoint['addressLine1'],
                $pickupPoint['title'] ?? null,
                $pickupPoint['image'] ?? null,
                isset($pickupPoint['lat']) ? (float) $pickupPoint['lat'] : null,
                isset($pickupPoint['lng']) ? (float) $pickupPoint['lng'] : null,
                $pickupPoint['postalCode'] ?? null,
                $pickupPoint['country'] ?? null,
                $pickupPoint['note'] ?? null,
                $pickupPoint['addressLine2'] ?? null,
                $pickupPoint['expectedDeliveryTime'] ?? null,
                $pickupPoint['region'] ?? null,
            );
        }
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * @return PickupPointDTO[]
     */
    public function getPickupPoints(): array
    {
        return $this->pickupPoints;
    }

    private function validatePickupPoints(array $pickupPoints): void
    {
        Assert::notEmpty($pickupPoints);

        foreach ($pickupPoints as $pickupPoint) {
            foreach ($this->getRequiredFields() as $field) {
                $value = $pickupPoint[$field] ?? null;

                $message = sprintf(
                    'Field %s expected to be a string. Got: %s',
                    $field,
                    $value
                );

                Assert::string($value, $message);
            }
        }
    }

    private function getRequiredFields(): array
    {
        return [
            'id',
            'type',
            'name',
            'addressLine1',
        ];
    }
}
