<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Integration\Service;

use Answear\BoxNowBundle\Client\Client;
use Answear\BoxNowBundle\Client\RequestTransformer;
use Answear\BoxNowBundle\ConfigProvider;
use Answear\BoxNowBundle\Logger\BoxNowLogger;
use Answear\BoxNowBundle\Serializer\Serializer;
use Answear\BoxNowBundle\Tests\MockGuzzleTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ServiceTestCase extends TestCase
{
    use MockGuzzleTrait;

    protected Serializer $serializer;
    protected Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->setSerializer(new Serializer());
    }

    protected function getLogger(): LoggerInterface
    {
        $expected = $this->getLoggerStream();

        $actualMessage = null;

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info')
            ->with(
                $this->callback(
                    static function (string $message) use ($expected, &$actualMessage): bool {
                        self::assertIsArray($expected[$message] ?? null);
                        $actualMessage = $message;

                        return true;
                    }
                ),
                $this->callback(
                    static function (array $context = []) use ($expected, &$actualMessage): bool {
                        self::assertIsString($context['requestId']);
                        unset($context['requestId']);

                        self::assertSame($expected[$actualMessage] ?? [], $context);

                        return true;
                    }
                )
            );

        return $logger;
    }

    protected function getConfigProvider(): ConfigProvider
    {
        return new ConfigProvider('clientId', 'clientSecret');
    }

    protected function setSerializer(Serializer $serializer): void
    {
        $this->serializer = $serializer;
    }

    protected function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    protected function setClient(Client|null $client = null, bool $withLogger = false): void
    {
        $this->client = $client ?? new Client(
            new RequestTransformer(
                $this->getSerializer(),
                $this->getConfigProvider(),
            ),
            new BoxNowLogger($withLogger ? $this->getLogger() : new NullLogger()),
            $this->setupGuzzleClient()
        );
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    protected function getLoggerStream(): array
    {
        return [];
    }
}
