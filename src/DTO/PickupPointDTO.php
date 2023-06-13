<?php

namespace Answear\BoxNowBundle\DTO;

class PickupPointDTO
{
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly string $name,
        private readonly string $address,
        private readonly ?string $title = null,
        private readonly ?string $image = null,
        private readonly ?float $latitude = null,
        private readonly ?float $longitude = null,
        private readonly ?string $postalCode = null,
        private readonly ?string $country = null,
        private readonly ?string $note = null,
        private readonly ?string $additionalAddress = null,
        private readonly ?string $expectedDeliveryTime = null,
        private readonly ?string $region = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAdditionalAddress(): ?string
    {
        return $this->additionalAddress;
    }

    public function getFullAddress(string $separator = ' '): string
    {
        return $this->address . $separator . $this->additionalAddress;
    }

    public function getExpectedDeliveryTime(): ?string
    {
        return $this->expectedDeliveryTime;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }
}
