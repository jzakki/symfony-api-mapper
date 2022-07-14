<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Exception\TypeException;
use SymfonyApiMapper\Property\PropertyMap;
use SymfonyApiMapper\Wrapper\ObjectWrapper;
use Symfony\Component\Yaml\Yaml;

class JsonMapper implements MapperInterface 
{

    /** @var callable|null */
    private $propertyMapper;

    /** @param Callable|null $propertyMapper */
    public function __construct(callable $propertyMapper = null)
    {
        $this->propertyMapper = $propertyMapper;
    }

    /**
     * @param Callable $propertyMapper
     * @return MapperInterface 
     */
    public function setPropertyMapper(callable $propertyMapper): MapperInterface 
    {
        $this->propertyMapper = $propertyMapper;

        return $this;
    }

    /**
     * @param \stdClass $json
     * @param mixed $object
     * @return string
     */
    public function map(\stdClass $json, $object)
    {
        if(! \is_object($object)) {
            throw TypeException::forArgument(__METHOD__, 'object', $object, 2, '$object');
        }

        // This is a temporary script 
        $map = Yaml::parseFile(dirname(__DIR__,1).'/config/map.yaml');

        $propertyMap = new PropertyMap();
        $handler = $this->resolve();
        $handler($json, new ObjectWrapper($object), $propertyMap, $map, $this);
    }

    /** @return Callable */
    public function resolve(): callable
    {
        if(is_null($this->propertyMapper)){
            throw new \RuntimeException('Property mapper has not been defined');
        }
        return $this->propertyMapper;
    }
} 