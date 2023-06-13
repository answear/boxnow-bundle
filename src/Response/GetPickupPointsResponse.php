<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Response;

use Answear\BoxNowBundle\DTO\PickupPointDTO;
use Webmozart\Assert\Assert;

class GetPickupPointsResponse implements ResponseInterface
{
    /** @var PickupPointDTO[] */
    private array $pickupPoints = [];

    public function __construct(private readonly array $data)
    {
        $this->handleData();
    }

    public function handleData(): void
    {
        $this->validatePickupPoints($this->data['data'] ?? []);

        foreach ($this->data['data'] as $pickupPoint) {
            $this->pickupPoints[] = new PickupPointDTO(
                id: $pickupPoint['id'],
                type: $pickupPoint['type'],
                name: $pickupPoint['name'],
                address: $pickupPoint['addressLine1'],
                title: $pickupPoint['title'] ?? null,
                image: $pickupPoint['image'] ?? null,
                latitude: $pickupPoint['lat'] ?? null,
                longitude: $pickupPoint['lng'] ?? null,
                postalCode: $pickupPoint['postalCode'] ?? null,
                country: $pickupPoint['country'] ?? null,
                note: $pickupPoint['note'] ?? null,
                additionalAddress: $pickupPoint['addressLine2'] ?? null,
                expectedDeliveryTime: $pickupPoint['expectedDeliveryTime'] ?? null,
                region: $pickupPoint['region'] ?? null,
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
