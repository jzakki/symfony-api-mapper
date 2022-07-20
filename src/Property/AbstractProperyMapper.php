<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Enum\ScalarType;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use SymfonyApiMapper\MapperInterface;
use SymfonyApiMapper\Enum\Visibility;
use SymfonyApiMapper\Exception\ClassFactoryException;
use SymfonyApiMapper\Exception\TypeException;
use SymfonyApiMapper\Helpers\IScalarCaster;
use SymfonyApiMapper\Helpers\ScalarCaster;

abstract class AbstractPropertyMapper
{

    /** @var FactoryRegistry */
    private $classFactoryRegistry;
    /** @var FactoryRegistry */
    private $nonInstantiableTypeResolver;
    /** @var IScalarCaster */
    private $scalarCaster;

    public function __construct(
        FactoryRegistry $classFactoryRegistry = null,
        FactoryRegistry $nonInstantiableTypeResolver = null,
        IScalarCaster $scalarCaster = null)
    {
        if ($classFactoryRegistry === null) {
            $classFactoryRegistry = FactoryRegistry::withNativePhpClassesAdded();
        }

        if ($nonInstantiableTypeResolver === null) {
            $nonInstantiableTypeResolver = new FactoryRegistry();
        }
        if ($scalarCaster === null) {
            $scalarCaster = new ScalarCaster();
        }

        $this->classFactoryRegistry = $classFactoryRegistry;
        $this->nonInstantiableTypeResolver = $nonInstantiableTypeResolver;
        $this->scalarCaster = $scalarCaster;
    }

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
    public function mapPropertyValue(MapperInterface $mapper, Property $property, $value)
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
                    if ($this->propertyTypeAndValueTypeAreScalarAndSameType($type, $firstValue)) {
                        $scalarType = new ScalarType($type->getType());
                        return \array_map(function ($v) use ($scalarType) {
                            return $this->scalarCaster->cast($scalarType, $v);
                        }, $value);
                    }

                    // Array of registered class @todo how do you know it was the correct type?
                    if ($this->classFactoryRegistry->hasFactory($type->getType())) {
                        return \array_map(function ($v) use ($type) {
                            return $this->classFactoryRegistry->create($type->getType(), $v);
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
                if ($this->propertyTypeAndValueTypeAreScalarAndSameType($type, $value)) {
                    return $this->scalarCaster->cast(new ScalarType($type->getType()), $value);
                }

                // Single registered class @todo how do you know it was the correct type?
                if ($this->classFactoryRegistry->hasFactory($type->getType())) {
                    return $this->classFactoryRegistry->create($type->getType(), $value);
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

        if (PHP_VERSION_ID >= 80100 && enum_exists($type->getType())) {
            if ($type->isArray()) {
                return $this->mapToArrayOfEnum($type->getType(), $value);
            }
            return $this->mapToEnum($type->getType(), $value);
        }

        if ($this->classFactoryRegistry->hasFactory($type->getType())) {
            if ($type->isArray()) {
                return \array_map(function ($v) use ($type) {
                    return $this->classFactoryRegistry->create($type->getType(), $v);
                }, $value);
            }
            return $this->classFactoryRegistry->create($type->getType(), $value);
        }

        if ($type->isArray() && (class_exists($type->getType()) || interface_exists($type->getType()))) {
            return $this->mapToArrayOfObjects($type->getType(), $value, $mapper);
        }

        if (class_exists($type->getType()) || interface_exists($type->getType())) {
            return $this->mapToObject($type->getType(), $value, $mapper);
        }

        throw new \Exception("Unable to map to {$type->getType()}");
    }

    /**
     * @param PropertyType $type
     * @param mixed $value
     * @psalm-assert-if-true scalar $value
     */
    private function propertyTypeAndValueTypeAreScalarAndSameType(PropertyType $type, $value): bool
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
     * @template T
     * @psalm-param class-string<T> $type
     * @param mixed $value
     * @return T
     */
    private function mapToEnum(string $type, $value)
    {
        return call_user_func("{$type}::from", $value);
    }

    /**
     * @template T
     * @psalm-param class-string<T> $type
     * @param mixed $value
     * @return T[]
     */
    private function mapToArrayOfEnum(string $type, $value): array
    {
        return \array_map(function ($val) use ($type) {
            return $this->mapToEnum($type, $val);
        }, (array) $value);
    }

    /**
     * @template T
     * @psalm-param class-string<T> $type
     * @param mixed $value
     * @return T
     */
    private function mapToObject(string $type, $value, MapperInterface $mapper)
    {
        if (! class_exists($type) && ! interface_exists($type)) {
            throw TypeException::forArgument(__METHOD__, 'class-string', $type, 1, '$type');
        }

        $reflectionType = new \ReflectionClass($type);
        if (!$reflectionType->isInstantiable()) {
            return $this->resolveUnInstantiableType($type, $value, $mapper);
        }

        $instance = new $type();
        $mapper->map($value, $instance);
        return $instance;
    }

    /**
     * @template T
     * @psalm-param class-string<T> $type
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

    /**
     * @template T
     * @psalm-param class-string<T> $type
     * @param mixed $value
     * @return T
     */
    private function resolveUnInstantiableType(string $type, $value, MapperInterface $mapper)
    {
        try {
            $instance = $this->nonInstantiableTypeResolver->create($type, $value);
            $mapper->map($value, $instance);
            return $instance;
        } catch (ClassFactoryException $e) {
            throw new \RuntimeException(
                "Unable to resolve un-instantiable {$type} as no factory was registered",
                0,
                $e
            );
        }
    }


}