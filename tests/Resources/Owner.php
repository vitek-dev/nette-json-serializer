<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests\Resources;

final class Owner
{
    public ?string $email;

    public function __construct(
        public string $fullName,
        public string $phone,
    ) {
    }
}