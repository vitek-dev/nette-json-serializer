<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests;

use PHPUnit\Framework\Attributes\DataProvider;

final class SimpleTest extends BaseTestCase
{
    #[DataProvider('dataProvider')]
    public function testDecode(mixed $value, string $json): void
    {
        $this->assertSame(
            $this->buildSerializer()->decode($json),
            $value,
        );
    }

    #[DataProvider('dataProvider')]
    public function testSerializer(mixed $value, string $json): void
    {
        $this->assertSame(
            $this->buildSerializer()->serialize($value),
            $json,
        );
    }

    /** @return array<mixed, mixed> */
    public static function dataProvider(): iterable
    {
        return [
            [
                'hello-world',
                '"hello-world"',
            ],
            [
                123,
                '123',
            ],
            [
                123.456,
                '123.456',
            ],
            [
                true,
                'true',
            ],
            [
                false,
                'false',
            ],
            [
                null,
                'null',
            ],
            [
                ['foo', 'bar'],
                '["foo","bar"]',
            ],
            [
                ['foo' => 'bar', 'baz' => 42],
                '{"foo":"bar","baz":42}',
            ],
        ];
    }
}