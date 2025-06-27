<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Integration\Service;

use Answear\BoxNowBundle\Enum\RegionEnum;
use Answear\BoxNowBundle\Service\PickupPointService;
use Answear\BoxNowBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Webmozart\Assert\InvalidArgumentException;

class PickupPointByRegionServiceTest extends ServiceTestCase
{
    #[Test]
    public function successfulListAllPickupPoints(): void
    {
        $this->setClient(withLogger: true);
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/success-by-region.json')
            )
        );

        $pickupPoints = $service->getAllByRegion(RegionEnum::Croatia);
        $actualData = [];

        foreach ($pickupPoints as $pickupPoint) {
            $actualData[] = [
                'id' => $pickupPoint->id,
                'type' => $pickupPoint->type,
                'name' => $pickupPoint->name,
                'address' => $pickupPoint->address,
                'title' => $pickupPoint->title,
                'image' => $pickupPoint->image,
                'latitude' => $pickupPoint->latitude,
                'longitude' => $pickupPoint->longitude,
                'postalCode' => $pickupPoint->postalCode,
                'country' => $pickupPoint->country,
                'note' => $pickupPoint->note,
                'additionalAddress' => $pickupPoint->additionalAddress,
                'expectedDeliveryTime' => $pickupPoint->expectedDeliveryTime,
                'region' => $pickupPoint->region,
            ];

            self::assertSame(
                $pickupPoint->address . ', ' . $pickupPoint->additionalAddress,
                $pickupPoint->getFullAddress(', ')
            );
        }

        $this->assertJsonStringEqualsJsonString(
            json_encode($actualData, JSON_THROW_ON_ERROR),
            FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/assert-valid-data-by-region.json'),
        );
    }

    #[Test]
    public function invalidPickupPointIdFieldValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field id expected to be a string. Got: ');

        $this->setClient();
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/invalid-id-field-value.json')
            )
        );

        $service->getAllByRegion(RegionEnum::Cyprus);
    }

    protected function getLoggerStream(): array
    {
        return [
            '[BOXNOW] Request - /v1/apms_hr-HR.json' => [
                'endpoint' => '/v1/apms_hr-HR.json',
                'uri' => [
                    'path' => '/v1/apms_hr-HR.json',
                    'query' => [],
                ],
                'body' => [],
            ],
            '[BOXNOW] Response - /v1/apms_hr-HR.json' => [
                'endpoint' => '/v1/apms_hr-HR.json',
                'uri' => [
                    'path' => '/v1/apms_hr-HR.json',
                    'query' => [],
                ],
                'response' => '--- HUGE CONTENT SKIPPED ---',
            ],
        ];
    }

    private function getService(): PickupPointService
    {
        return new PickupPointService($this->getClient(), $this->getSerializer());
    }
}
