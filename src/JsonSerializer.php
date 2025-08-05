<?php

declare(strict_types=1);

namespace VitekDev\Serializer;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final readonly class JsonSerializer
{
    public private(set) Serializer $serializer;

    public function __construct()
    {
        $classMetadata = new ClassMetadataFactory(
            new AttributeLoader(),
        );

        $this->serializer = new Serializer(
            normalizers: [
                new BackedEnumNormalizer(),
                new PropertyNormalizer(
                    classMetadataFactory: $classMetadata,
                    propertyTypeExtractor: new PropertyInfoExtractor(
                        typeExtractors: [
                            new PhpDocExtractor(),
                            new ReflectionExtractor(),
                        ],
                    ),
                    classDiscriminatorResolver: new ClassDiscriminatorFromClassMetadata(
                        classMetadataFactory: $classMetadata,
                    ),
                ),
                new ArrayDenormalizer(),
            ],
            encoders: [
                new JsonEncoder(),
            ],
        );
    }

    /** @throws \Symfony\Component\Serializer\Exception\ExceptionInterface */
    public function serialize(mixed $data): string
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * @template T of class-string
     * @param class-string<T> $type
     * @return T
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function deserialize(string|MessageInterface|StreamInterface $data, string $type): mixed
    {
        if ($data instanceof MessageInterface) {
            $data = $data->getBody();
        }

        if ($data instanceof StreamInterface) {
            $data = $data->getContents();
        }

        return $this->serializer->deserialize($data, $type, 'json');
    }

    /** @throws \Symfony\Component\Serializer\Exception\ExceptionInterface */
    public function decode(string $data): mixed
    {
        return $this->serializer->decode($data, 'json');
    }
}