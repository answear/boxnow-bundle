<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Integration\Service;

use Answear\BoxNowBundle\Service\PickupPointService;
use Answear\BoxNowBundle\Tests\Util\FileTestUtil;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Webmozart\Assert\InvalidArgumentException;

class PickupPointServiceTest extends ServiceTestCase
{
    /**
     * @test
     */
    public function successfulListAllPickupPoints(): void
    {
        $this->setClient(withLogger: true);
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/success.json')
            )
        );

        $pickupPoints = $service->getAll('token');
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

            $this->assertSame(
                $pickupPoint->address . ', ' . $pickupPoint->additionalAddress,
                $pickupPoint->getFullAddress(', ')
            );
        }

        $this->assertSame(
            FileTestUtil::decodeJsonFromFile(__DIR__ . '/../../data/pickup-points/assert-valid-data.json'),
            $actualData
        );
    }

    /**
     * @test
     */
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

        $service->getAll('token');
    }

    /**
     * @test
     */
    public function invalidPickupPointNameFieldValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field name expected to be a string. Got: ');

        $this->setClient();
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/invalid-name-field-value.json')
            )
        );

        $service->getAll('token');
    }

    /**
     * @test
     */
    public function invalidPickupPointAddressFieldValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field addressLine1 expected to be a string. Got: ');

        $this->setClient();
        $service = $this->getService();

        $this->mockGuzzleResponse(
            new Response(
                status: SymfonyResponse::HTTP_OK,
                body: FileTestUtil::getFileContents(__DIR__ . '/../../data/pickup-points/invalid-address-field-value.json')
            )
        );

        $service->getAll('token');
    }

    private function getService(): PickupPointService
    {
        return new PickupPointService($this->getClient(), $this->getSerializer());
    }

    protected function getLoggerStream(): array
    {
        return [
            '[BOXNOW] Request - /api/v1/destinations' => [
                'endpoint' => '/api/v1/destinations',
                'uri' => [
                    'path' => '/api/v1/destinations',
                    'query' => [],
                ],
                'body' => [],
            ],
            '[BOXNOW] Response - /api/v1/destinations' => [
                'endpoint' => '/api/v1/destinations',
                'uri' => [
                    'path' => '/api/v1/destinations',
                    'query' => [],
                ],
                'response' => '--- HUGE CONTENT SKIPPED ---',
            ],
        ];
    }
}
