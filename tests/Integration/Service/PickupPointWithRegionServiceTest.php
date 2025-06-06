<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Integration\Service;

use Answear\BoxNowBundle\Service\PickupPointService;
use Answear\BoxNowBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Webmozart\Assert\InvalidArgumentException;

class PickupPointWithRegionServiceTest extends ServiceTestCase
{
    #[Test]
    public function successfulListAllPickupPoints(): void
    {
        $this->setClient(withLogger: true);
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/success-with-region.json')
            )
        );

        $pickupPoints = $service->getAllWithRegion('token', 'region');
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

        $this->assertSame(
            FileTestUtil::decodeJsonFromFile(__DIR__ . '/../../data/pickup-points/assert-valid-data-with-region.json'),
            $actualData
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

        $service->getAllWithRegion('token', 'region');
    }

    protected function getLoggerStream(): array
    {
        return [
            '[BOXNOW] Request - /api/v1/apms:customerSearch' => [
                'endpoint' => '/api/v1/apms:customerSearch',
                'uri' => [
                    'path' => '/api/v1/apms:customerSearch',
                    'query' => [
                        'regionLanguageTag' => 'region',
                    ],
                ],
                'body' => [],
            ],
            '[BOXNOW] Response - /api/v1/apms:customerSearch' => [
                'endpoint' => '/api/v1/apms:customerSearch',
                'uri' => [
                    'path' => '/api/v1/apms:customerSearch',
                    'query' => [
                        'regionLanguageTag' => 'region',
                    ],
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
