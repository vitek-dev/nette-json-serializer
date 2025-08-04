<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests\Resources;

class Garage
{
    /** @var \VitekDev\Serializer\Tests\Resources\Car[] */
    public array $hiddenCars = [];

    /** @param \VitekDev\Serializer\Tests\Resources\Car[] $publicCars */
    public function __construct(
        public string $garageName,
        public array $publicCars,
        public ?string $infoBanner,
    ) {
    }
}