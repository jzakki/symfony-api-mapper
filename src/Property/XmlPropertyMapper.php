<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;
use SymfonyApiMapper\Helpers\DocBlockAnnotation;
use SymfonyApiMapper\Helpers\YamlMap;

class XmlPropertyMapper extends AbstractPropertyMapper implements PropertyMapperInterface
{
    
    public function __invoke(
        \stdClass $xml, 
        ObjectWrapper $object, 
        PropertyMap $propertyMap, 
        MapperInterface $xmlMapper): void
    {

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

            $value = $this->mapPropertyValue($xmlMapper, $property, $value);
            $this->setValue($object, $property, $value);
        }
        
    }

}