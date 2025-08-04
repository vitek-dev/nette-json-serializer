<?php

declare(strict_types=1);

namespace VitekDev\Serializer\Tests;

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use VitekDev\Serializer\JsonSerializer;

abstract class BaseTestCase extends TestCase
{
    protected function bootContainer(): Container
    {
        $configurator = new Configurator();
        $configurator->setTempDirectory(sys_get_temp_dir());
        $configurator->addConfig(__DIR__ . '/Resources/test.neon');

        return $configurator->createContainer();
    }

    protected function buildSerializer(): JsonSerializer
    {
        return $this->bootContainer()->getByType(JsonSerializer::class);
    }
}