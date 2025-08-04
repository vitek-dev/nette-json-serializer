<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests\Resources;

final readonly class CarPlate
{
    public function __construct(
        public string $plateNumber,
    ) {
    }
}