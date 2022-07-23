<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Factories\MapperInterface;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\Helpers\DocBlockAnnotation;

class XmlPropertyMapper extends AbstractPropertyMapper
{
    
    public function __invoke(
        \stdClass $xml, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap, 
        MapperInterface $xmlMapper): void
    {

        $reflectedObject = $object->getReflectedObject();
        $namespace = $reflectedObject->getNamespaceName();
        $yamlMap = $xmlMapper->getYamlMap();
        $docBlockAnnotation = new DocBlockAnnotation();
        $propertyMap->merge($docBlockAnnotation->buildPropertyMapObjectFromDocBlockAnnotations($object, $yamlMap));
        $values = (array) $xml;

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

            $value = $this->mapPropertyValue($xmlMapper, $property, $value, $namespace);
            $this->setValue($object, $property, $value);
        }
        
    }

}