<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;
use SymfonyApiMapper\Helpers\DocBlockAnnotation;
use SymfonyApiMapper\Helpers\YamlMap;

class JsonPropertyMapper extends AbstractPropertyMapper implements PropertyMapperInterface
{
    
    public function __invoke(
        \stdClass $json, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap,
        MapperInterface $jsonMapper): void
    {
        $docBlockAnnotation = new DocBlockAnnotation();
        $propertyMap->merge($docBlockAnnotation->buildPropertyMapObjectFromDocBlockAnnotations($object));
        $values = (array) $json;
        $yamlMap = new YamlMap();

        foreach($values as $key => $value){
            $propertyName = $yamlMap->getApiEqualsToKey($object->getName(), $key);
            if(is_null($propertyName)) continue;
            if (! $propertyMap->hasProperty($propertyName)) continue;

            $property = $propertyMap->getProperty($propertyName);
            if (! $property->isNullable() && \is_null($value)) {
                throw new \RuntimeException(
                    "Null provided in json where {$object->getName()}::{$key} doesn't allow null value"
                );
            }
            if ($property->isNullable() && \is_null($value)) {
                $this->setValue($object, $property, null);
                continue;
            }

            $value = $this->mapPropertyValue($jsonMapper, $property, $value);
            $this->setValue($object, $property, $value);
        }
        
    }

}