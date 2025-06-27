<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Request;

use Answear\BoxNowBundle\Enum\RegionEnum;

readonly class GetPickupPointsByRegionRequest implements RequestInterface
{
    /* please use locationapi url */
    private const string ENDPOINT = '/v1/apms_REGION_PLACEHOLDER.json';
    private const string HTTP_METHOD = 'GET';

    public function __construct(private RegionEnum $region)
    {
    }

    public function getEndpoint(): string
    {
        return str_replace('REGION_PLACEHOLDER', $this->region->value, self::ENDPOINT);
    }

    public function getMethod(): string
    {
        return self::HTTP_METHOD;
    }

    public function getUrlQuery(): ?string
    {
        return null;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
