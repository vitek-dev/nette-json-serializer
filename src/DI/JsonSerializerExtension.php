<?php

declare(strict_types=1);

namespace VitekDev\Serializer\DI;

use VitekDev\Serializer\JsonSerializer;

class JsonSerializerExtension extends \Nette\DI\CompilerExtension
{
    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('serializer'))
            ->setFactory(JsonSerializer::class);
    }
}