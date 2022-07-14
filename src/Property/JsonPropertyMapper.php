<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;

class JsonPropertyMapper implements PropertyMapperInterface 
{
    public function __invoke(
        \stdClass $json, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap,
        array $map,
        MapperInterface $jsonMapper): void
    {

        dd($map);
        
    }
}