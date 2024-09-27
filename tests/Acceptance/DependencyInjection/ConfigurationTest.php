<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Tests\Acceptance\DependencyInjection;

use Answear\BoxNowBundle\ConfigProvider;
use Answear\BoxNowBundle\DependencyInjection\AnswearBoxNowExtension;
use Answear\BoxNowBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    #[Test]
    #[DataProvider('provideValidConfig')]
    public function validTest(array $configs): void
    {
        $this->assertConfigurationIsValid($configs);

        $extension = $this->getExtension();

        $builder = new ContainerBuilder();
        $extension->load($configs, $builder);

        $configProviderDefinition = $builder->getDefinition(ConfigProvider::class);

        self::assertSame($configs[0]['clientId'], $configProviderDefinition->getArgument(0));
        self::assertSame($configs[0]['clientSecret'], $configProviderDefinition->getArgument(1));

        if (isset($configs[0]['apiUrl'])) {
            self::assertSame($configs[0]['apiUrl'], $configProviderDefinition->getArgument(2));
        }
    }

    #[Test]
    #[DataProvider('provideInvalidConfig')]
    public function invalidConfig(array $config, ?string $expectedMessage = null): void
    {
        $this->assertConfigurationIsInvalid(
            $config,
            $expectedMessage
        );
    }

    #[Test]
    #[DataProvider('provideInvalidLogger')]
    public function invalidLogger(array $configs, \Throwable $expectedException): void
    {
        $this->expectException(get_class($expectedException));
        $this->expectExceptionMessage($expectedException->getMessage());

        $this->assertConfigurationIsValid($configs);

        $extension = $this->getExtension();

        $builder = new ContainerBuilder();
        $extension->load($configs, $builder);
    }

    public static function provideInvalidConfig(): iterable
    {
        yield [
            [
                [],
            ],
            '"answear_box_now" must be configured.',
        ];

        yield [
            [
                [
                    'clientId' => 'test',
                ],
            ],
            '"answear_box_now" must be configured.',
        ];

        yield [
            [
                [
                    'clientSecret' => 'clientSecret',
                ],
            ],
            'answear_box_now" must be configured.',
        ];
    }

    public static function provideInvalidLogger(): iterable
    {
        yield [
            [
                [
                    'clientId' => 'clientId',
                    'clientSecret' => 'clientSecret',
                    'logger' => 'not-existing-service-name',
                ],
            ],
            new ServiceNotFoundException('not-existing-service-name'),
        ];
    }

    public static function provideValidConfig(): iterable
    {
        yield [
            [
                [
                    'clientId' => 'clientId',
                    'clientSecret' => 'clientSecret',
                ],
            ],
        ];

        yield [
            [
                [
                    'clientId' => 'clientId',
                    'clientSecret' => 'clientSecret',
                    'apiUrl' => 'apiUrl',
                ],
            ],
        ];

        yield [
            [
                [
                    'clientId' => 123,
                    'clientSecret' => 321,
                ],
            ],
        ];
    }

    protected function getContainerExtensions(): array
    {
        return [$this->getExtension()];
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    private function getExtension(): AnswearBoxNowExtension
    {
        return new AnswearBoxNowExtension();
    }
}
