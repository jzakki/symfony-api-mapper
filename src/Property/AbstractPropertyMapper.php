<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Enum\ScalarType;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\Enum\Visibility;
use SymfonyApiMapper\Exception\TypeException;
use SymfonyApiMapper\Factories\MapperInterface;
use SymfonyApiMapper\Helpers\IScalarCaster;
use SymfonyApiMapper\Helpers\ScalarCaster;

abstract class AbstractPropertyMapper
{

    /** @var IScalarCaster */
    private $scalarCaster;

    public function __construct(IScalarCaster $scalarCaster = null)
    {
        if ($scalarCaster === null) {
            $scalarCaster = new ScalarCaster();
        }
        $this->scalarCaster = $scalarCaster;
    }

    public abstract function __invoke(
        \stdClass $response,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        MapperInterface $mapperInterface
    ): void;

    /**
     * @param ObjectWrapper $object
     * @param Property $property
     * @param mixed $value
     */
    public function setValue(ObjectWrapper $object, Property $property, $value): void
    {

        if ($property->getVisibility()->equals(Visibility::PUBLIC())) {
            $object->getObject()->{$property->getName()} = $value;
            return;
        }

        $methodName = 'set' . \ucfirst($property->getName());
        if (\method_exists($object->getObject(), $methodName)) {
            $method = new \ReflectionMethod($object->getObject(), $methodName);
            $parameters = $method->getParameters();

            if (\is_array($value) && \count($parameters) === 1 && $parameters[0]->isVariadic()) {
                $object->getObject()->$methodName(...$value);
                return;
            }

            $object->getObject()->$methodName($value);
            return;
        }

        throw new \RuntimeException(
            "{$object->getName()}::{$property->getName()} is non-public and no setter method was found"
        );
    }

    /**
     * @param MapperInterface $mapper
     * @param Property $property
     * @param mixed $value
     * @return mixed
     */
    public function mapPropertyValue(MapperInterface $mapper, Property $property, $value, $namespace)
    {

        // For union types, loop through and see if value is a match with the type
        if (\count($property->getTypes()) > 1) {
            foreach ($property->getTypes() as $type) {
                if (\is_array($value) && $type->isArray() && count($value) === 0) {
                    return [];
                }

                if (\is_array($value) && $type->isArray()) {
                    $copy = $value;
                    $firstValue = \array_shift($copy);

                    /* Array of scalar values */
                    if ($this->sametypeAndValue($type, $firstValue)) {
                        $scalarType = new ScalarType($type->getType());
                        return \array_map(function ($v) use ($scalarType) {
                            return $this->scalarCaster->cast($scalarType, $v);
                        }, $value);
                    }

                    // Array of existing class @todo how do you know it was the correct type?
                    if (\class_exists($type->getType())) {
                        return \array_map(
                            static function ($v) use ($type, $mapper) {
                                $className = $type->getType();
                                $instance = new $className();
                                $mapper->map($v, $instance);
                                return $instance;
                            },
                            $value
                        );
                    }

                    continue;
                }

                // Single scalar value
                if ($this->sametypeAndValue($type, $value)) {
                    return $this->scalarCaster->cast(new ScalarType($type->getType()), $value);
                }

                // Single existing class @todo how do you know it was the correct type?
                if (\class_exists($type->getType())) {
                    return $this->mapToObject($type->getType(), $value, $mapper);
                }
            }
        }

        // No match was found (or there was only one option) lets assume the first is the right one.
        $types = $property->getTypes();
        $type = \array_shift($types);

        if ($type === null) {
            // Return the value as is as there is no type info.
            return $value;
        }

        if (ScalarType::isValid($type->getType())) {
            if ($type->isArray()) {
                return $this->mapToArrayOfScalarValue($type->getType(), $value);
            }
            return $this->mapToScalarValue($type->getType(), $value);
        }

        if ($type->isArray() && class_exists($type->getType())) {
            return $this->mapToArrayOfObjects($type->getType(), $value, $mapper);
        }

        if (class_exists($namespace.'\\'.$type->getType())) {
            return $this->mapToObject($namespace.'\\'.$type->getType(), $value, $mapper);
        }

        throw new \Exception("Unable to map to {$type->getType()}");
    }

    /**
     * @param PropertyType $type
     * @param mixed $value
     */
    private function sametypeAndValue(PropertyType $type, $value): bool
    {
        if (! \is_scalar($value) || ! ScalarType::isValid($type->getType())) {
            return false;
        }

        $valueType = \gettype($value);
        if ($valueType === 'double') {
            $valueType = 'float';
        }

        return $type->getType() === $valueType;
    }

    /**
     * @param mixed $value
     * @return string|bool|int|float
     */
    private function mapToScalarValue(string $type, $value)
    {
        $scalar = new ScalarType($type);
        return $this->scalarCaster->cast($scalar, $value);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return string[]|bool[]|int[]|float[]
     */
    private function mapToArrayOfScalarValue(string $type, $value): array
    {
        $scalar = new ScalarType($type);
        return \array_map(function ($v) use ($scalar) {
            return $this->scalarCaster->cast($scalar, $v);
        }, (array) $value);
    }

    /**
     * @param mixed $value
     * @return T
     */
    private function mapToObject(string $type, $value, MapperInterface $mapper)
    {
        if (! class_exists($type) && ! interface_exists($type)) {
            throw TypeException::forArgument(__METHOD__, 'class-string', $type, 1, '$type');
        }

        $instance = new $type();
        $mapper->map($value, $instance);
        return $instance;
    }

    /**
     * @param mixed $value
     * @return array<int, T>
     */
    private function mapToArrayOfObjects(string $type, $value, MapperInterface $mapper): array
    {
        return \array_map(
            function ($val) use ($type, $mapper) {
                return $this->mapToObject($type, $val, $mapper);
            },
            (array) $value
        );
    }

}