<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\Serializer;

use Answear\BoxNowBundle\Request\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer
{
    private const FORMAT = 'json';
    private const DATE_FORMAT = 'Y-m-d\\TH:i:s.uP';
    private SymfonySerializer $serializer;

    public function serialize(RequestInterface $request): string
    {
        return $this->getSerializer()->serialize(
            $request,
            self::FORMAT,
            [Normalizer\AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );
    }

    public function decodeResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getSerializer(): SymfonySerializer
    {
        if (!isset($this->serializer)) {
            $this->serializer = new SymfonySerializer(
                [
                    new Normalizer\CustomNormalizer(),
                    new Normalizer\DateTimeNormalizer(
                        [
                            Normalizer\DateTimeNormalizer::FORMAT_KEY => self::DATE_FORMAT,
                        ]
                    ),
                    new Normalizer\PropertyNormalizer(
                        null,
                        new CamelCaseToSnakeCaseNameConverter(),
                        new ReflectionExtractor()
                    ),
                    new Normalizer\ArrayDenormalizer(),
                ],
                [new JsonEncoder()]
            );
        }

        return $this->serializer;
    }
}
