<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;
use Symfony\Component\Yaml\Yaml;

class JsonPropertyMapper implements PropertyMapperInterface 
{
    public function __invoke(
        \stdClass $json, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap, 
        MapperInterface $jsonMapper): void
    {

        dd($json);
        
    }
}