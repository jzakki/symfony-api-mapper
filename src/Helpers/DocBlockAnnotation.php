<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Helpers;

use SymfonyApiMapper\Enum\Visibility;
use SymfonyApiMapper\Property\AnnotationMap;
use SymfonyApiMapper\Property\PropertyBuilder;
use SymfonyApiMapper\Property\PropertyMap;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\Helpers\YamlMap;

class DocBlockAnnotation
{
    
    private const DOC_BLOCK_REGEX = '/@(?P<name>[A-Za-z_-]+)[ \t]+(?P<value>[\w\[\]\\\\|]*).*$/m';

    public function buildPropertyMapObjectFromDocBlockAnnotations(ObjectWrapper $object, YamlMap $yamlMap): PropertyMap
    {
        $properties = $object->getReflectedObject()->getProperties();
        $propertyMap = new PropertyMap();
        
        foreach($properties as $property){

            $docBlock = $property->getDocComment();
            if($docBlock === false) continue;
            $propertyName = $property->getName();

            $annotationMap = self::parseDocBlockToAnnotationMap($docBlock);
            if (! $annotationMap->hasVar()) continue;
            $types = \explode('|', $annotationMap->getVar());
            $nullable = \in_array('null', $types, true);
            $types = \array_filter($types, static function (string $type) {
                return $type !== 'null';
            });
            $propertyBuilder = PropertyBuilder::new()
                ->setName($propertyName)
                ->setApiEqualsToName($yamlMap->getApiEqualsToName($object->getName(), $propertyName))
                ->setIsNullable($nullable)
                ->setVisibility(Visibility::fromReflectionProperty($property));

            /* Convert union type to mixed */
            if (\in_array('array', $types, true)) {
                $property = $propertyBuilder->addType('mixed', true)->build();
                $propertyMap->addProperty($property);
                continue;
            }

            foreach ($types as $type) {
                $type = \trim($type);
                $isArray = \substr($type, -2) === '[]';
                if ($isArray) {
                    $type = \substr($type, 0, -2);
                }
                $propertyBuilder->addType($type, $isArray);
            }

            $property = $propertyBuilder->build();
            $propertyMap->addProperty($property);

        }

        return $propertyMap;
    }

    private static function parseDocBlockToAnnotationMap(string $docBlock): AnnotationMap
    {
        // Strip away the start "/**' and ending "*/"
        if (strpos($docBlock, '/**') === 0) {
            $docBlock = \substr($docBlock, 3);
        }
        if (substr($docBlock, -2) === '*/') {
            $docBlock = \substr($docBlock, 0, -2);
        }
        $docBlock = \trim($docBlock);

        $var = null;
        if (\preg_match_all(self::DOC_BLOCK_REGEX, $docBlock, $matches)) {
            for ($x = 0, $max = count($matches[0]); $x < $max; $x++) {
                if ($matches['name'][$x] === 'var') {
                    $var = $matches['value'][$x];
                }
            }
        }

        return new AnnotationMap($var ?: null, [], null);
    }
}