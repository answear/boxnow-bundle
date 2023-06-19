<?php

namespace Answear\BoxNowBundle\DTO;

class PickupPointDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $name,
        public readonly string $address,
        public readonly ?string $title = null,
        public readonly ?string $image = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $country = null,
        public readonly ?string $note = null,
        public readonly ?string $additionalAddress = null,
        public readonly ?string $expectedDeliveryTime = null,
        public readonly ?string $region = null,
    ) {
    }

    public function getFullAddress(string $separator = ' '): string
    {
        return $this->address . $separator . $this->additionalAddress;
    }
}
