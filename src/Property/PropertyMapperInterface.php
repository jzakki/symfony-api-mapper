<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use stdClass;
use SymfonyApiMapper\MapperInterface;
use SymfonyApiMapper\Wrapper\ObjectWrapper;

interface PropertyMapperInterface
{
    public function __invoke(
        stdClass $response,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        MapperInterface $mapperInterface
    ): void;
}