<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests;

use VitekDev\Serializer\Tests\Resources\Brand;
use VitekDev\Serializer\Tests\Resources\Car;
use VitekDev\Serializer\Tests\Resources\CarPlate;
use VitekDev\Serializer\Tests\Resources\Garage;
use VitekDev\Serializer\Tests\Resources\Owner;

final class SerializeTest extends BaseTestCase
{
    public function testEncode(): void
    {
        $garage = $this->createGarage();

        $this->assertSame(<<<JSON
{"hiddenCars":[{"vinCode":null,"owner":null,"brand":"VOLKSWAGEN","model":"Golf","carPlate":{"plateNumber":"BBB 4321"}}],"garageName":"Frantovo a Pepovo","publicCars":[{"vinCode":null,"owner":{"email":"frantuv@email.cz","fullName":"Franta Nov\u00e1k","phone":"+420 123 456 789"},"brand":"SKODA","model":"Octavia","carPlate":{"plateNumber":"AAA 1234"}},{"vinCode":null,"owner":{"email":null,"fullName":"Pepa Nov\u00e1k","phone":"+420 987 654 321"},"brand":"SKODA","model":"Golf","carPlate":{"plateNumber":"XXA 7852"}}],"infoBanner":"Oliver is missing"}
JSON,
            $this->buildSerializer()->serialize($garage),
        );
    }

    private function createGarage(): Garage
    {
        $franta = $this->createPerson('Franta Novák', '+420 123 456 789', 'frantuv@email.cz');
        $pepa = $this->createPerson('Pepa Novák', '+420 987 654 321');

        $frantovo = $this->createCar(
            Brand::SKODA,
            'Octavia',
            'AAA 1234',
            $franta,
        );

        $pepovo = $this->createCar(
            Brand::SKODA,
            'Golf',
            'XXA 7852',
            $pepa,
        );

        $companys = $this->createCar(
            Brand::VOLKSWAGEN,
            'Golf',
            'BBB 4321',
        );

        $garage = new Garage(
            'Frantovo a Pepovo',
            [
                $frantovo,
                $pepovo,
            ],
            'Oliver is missing',
        );

        $garage->hiddenCars = [
            $companys,
        ];

        return $garage;
    }

    private function createPerson(string $fullName, string $phone, ?string $email = null): Owner
    {
        $owner = new Owner($fullName, $phone);
        $owner->email = $email;

        return $owner;
    }

    private function createCar(Brand $brand, string $model, string $plateNumber, ?Owner $owner = null, ?string $vinCode = null): Car
    {
        $car = new Car(
            $brand,
            $model,
            new CarPlate($plateNumber),
        );

        $car->owner = $owner;
        $car->vinCode = $vinCode;

        return $car;
    }
}