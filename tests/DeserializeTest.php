<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use VitekDev\Serializer\Tests\Resources\Car;
use VitekDev\Serializer\Tests\Resources\CarPlate;
use VitekDev\Serializer\Tests\Resources\Garage;
use VitekDev\Serializer\Tests\Resources\Owner;

final class DeserializeTest extends BaseTestCase
{
    public function testDeserialize(): void
    {
        $garage = $this->buildSerializer()->deserialize(
            $this->getTestString(),
            Garage::class,
        );

        self::assertTrue($this->doTestAssertions($garage));
    }

    public function testDeserializeMessage(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($this->getTestString());

        $message = $this->createMock(MessageInterface::class);
        $message
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $garage = $this->buildSerializer()->deserialize(
            $message,
            Garage::class,
        );

        self::assertTrue($this->doTestAssertions($garage));
    }

    public function testDeserializeStream(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($this->getTestString());

        $garage = $this->buildSerializer()->deserialize(
            $stream,
            Garage::class,
        );

        self::assertTrue($this->doTestAssertions($garage));
    }

    private function getTestString(): string
    {
        return <<<JSON
{"hiddenCars":[{"vinCode":null,"owner":null,"brand":"VOLKSWAGEN","model":"Golf","carPlate":{"plateNumber":"BBB 4321"}}],"garageName":"Frantovo a Pepovo","publicCars":[{"vinCode":null,"owner":{"email":"frantuv@email.cz","fullName":"Franta Nov\u00e1k","phone":"+420 123 456 789"},"brand":"SKODA","model":"Octavia","carPlate":{"plateNumber":"AAA 1234"}},{"vinCode":null,"owner":{"email":null,"fullName":"Pepa Nov\u00e1k","phone":"+420 987 654 321"},"brand":"VOLKSWAGEN","model":"Golf","carPlate":{"plateNumber":"XXA 7852"}}],"infoBanner":"Oliver is missing"}
JSON;
    }

    private function doTestAssertions(Garage $garage): bool
    {
        self::assertSame('Frantovo a Pepovo', $garage->garageName);
        self::assertSame('Oliver is missing', $garage->infoBanner);

        self::assertCount(1, $garage->hiddenCars);
        self::assertInstanceOf(Car::class, $garage->hiddenCars[0]);
        self::assertNull($garage->hiddenCars[0]->vinCode);
        self::assertNull($garage->hiddenCars[0]->owner);
        self::assertSame('VOLKSWAGEN', $garage->hiddenCars[0]->brand->value);
        self::assertSame('Golf', $garage->hiddenCars[0]->model);
        self::assertInstanceOf(CarPlate::class, $garage->hiddenCars[0]->carPlate);
        self::assertSame('BBB 4321', $garage->hiddenCars[0]->carPlate->plateNumber);

        self::assertCount(2, $garage->publicCars);
        self::assertInstanceOf(Car::class, $garage->publicCars[0]);
        self::assertNull($garage->publicCars[0]->vinCode);
        self::assertInstanceOf(Owner::class, $garage->publicCars[0]->owner);
        self::assertSame('frantuv@email.cz', $garage->publicCars[0]->owner->email);
        self::assertSame('Franta Novák', $garage->publicCars[0]->owner->fullName);
        self::assertSame('+420 123 456 789', $garage->publicCars[0]->owner->phone);
        self::assertSame('SKODA', $garage->publicCars[0]->brand->value);
        self::assertSame('Octavia', $garage->publicCars[0]->model);
        self::assertInstanceOf(CarPlate::class, $garage->publicCars[0]->carPlate);
        self::assertSame('AAA 1234', $garage->publicCars[0]->carPlate->plateNumber);

        self::assertInstanceOf(Car::class, $garage->publicCars[1]);
        self::assertNull($garage->publicCars[1]->vinCode);
        self::assertInstanceOf(Owner::class, $garage->publicCars[1]->owner);
        self::assertNull($garage->publicCars[1]->owner->email);
        self::assertSame('Pepa Novák', $garage->publicCars[1]->owner->fullName);
        self::assertSame('+420 987 654 321', $garage->publicCars[1]->owner->phone);
        self::assertSame('VOLKSWAGEN', $garage->publicCars[1]->brand->value);
        self::assertSame('Golf', $garage->publicCars[1]->model);
        self::assertInstanceOf(CarPlate::class, $garage->publicCars[1]->carPlate);
        self::assertSame('XXA 7852', $garage->publicCars[1]->carPlate->plateNumber);

        return true;
    }
}