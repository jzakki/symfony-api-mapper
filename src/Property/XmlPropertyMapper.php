<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use stdClass;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;

class XmlPropertyMapper implements PropertyMapperInterface 
{
    public function __invoke(
        stdClass $xml, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap, 
        MapperInterface $xmlnMapper): void
    {
        
    }
}