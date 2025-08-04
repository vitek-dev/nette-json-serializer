<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests\Resources;

final class Car
{
    public ?string $vinCode;

    public ?Owner $owner;

    public function __construct(
        public Brand $brand,
        public string $model,
        public CarPlate $carPlate,
    ) {
    }
}