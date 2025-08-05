# JSON serializer

Preconfigured symfony/serializer for Nette Framework.

## Features
- Serialize and deserialize PHP objects to/from JSON
- Handles nested objects and arrays (via PHPDoc annotations)
- Enums support
- Discriminators
- Syntax sugar for Guzzle: Ability to directly deserialize MessageInterface/StreamInterface

## Requirements
- PHP 8.4 or higher

## Installation

```shell
composer require vitek-dev/nette-json-serializer
```

```neon
extensions:
    vd.serializer: VitekDev\Serializer\DI\JsonSerializerExtension
```

## Known issues

### Class with no properties

Deserialization of class with no properties will end up with NotNormalizableValueException

```php
class Garage {
    /**
     * @param Car[] $cars
     */
    public function __construct(
        public array $cars,
    ) {}
}

class Car {
    // No properties
}
```