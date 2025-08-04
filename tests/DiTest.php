<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests;

use Symfony\Component\Serializer\SerializerInterface;
use VitekDev\Serializer\JsonSerializer;

final class DiTest extends BaseTestCase
{
    public function testAutowiring(): void
    {
        $autowired = $this->bootContainer()->findAutowired(JsonSerializer::class);

        $this->assertCount(1, $autowired);
        $this->assertSame('testExtension.serializer', $autowired[0]);
    }

    public function testHasSerializer(): void
    {
        $this->assertInstanceOf(
            SerializerInterface::class,
            $this->buildSerializer()->serializer,
        );
    }
}