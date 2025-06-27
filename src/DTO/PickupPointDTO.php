<?php

namespace Answear\BoxNowBundle\DTO;

readonly class PickupPointDTO
{
    public function __construct(
        public string $id,
        public string $type,
        public string $state,
        public string $name,
        public string $address,
        public ?string $title = null,
        public ?string $image = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $postalCode = null,
        public ?string $country = null,
        public ?string $note = null,
        public ?string $additionalAddress = null,
        public ?string $expectedDeliveryTime = null,
        public ?string $region = null,
    ) {
    }

    public function getFullAddress(string $separator = ' '): string
    {
        return $this->address . $separator . $this->additionalAddress;
    }
}
