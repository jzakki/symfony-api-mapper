<?php 

declare(strict_types=1);

namespace SymfonyApiMapper\Factories;

use SymfonyApiMapper\Exception\TypeException;
use SymfonyApiMapper\Helpers\YamlMap;
use SymfonyApiMapper\Property\PropertyMap;
use SymfonyApiMapper\Wrapper\ObjectWrapper;

class XmlMapper implements MapperInterface
{

    /** @var callable|null */
    private $propertyMapper;

    /** @var YamlMap */
    private $yamlMap;

    /** @param Callable|null $propertyMapper */
    public function __construct(callable $propertyMapper = null)
    {
        $this->propertyMapper = $propertyMapper;
    }

    /**
     * @param $xml
     * @param mixed $object
     * @return string
     */
    public function map($xml, $object)
    {
        if(! \is_object($xml)) {
            $xml = json_decode(json_encode(simplexml_load_string($xml)));
        }

        if(! \is_object($object)) {
            throw TypeException::forArgument(__METHOD__, 'object', $object, 2, '$object');
        }
        
        $propertyMap = new PropertyMap();
        $handler = $this->resolve();
        $handler($xml, new ObjectWrapper($object), $propertyMap, $this);
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
     * @return YamlMap
     */
    public function getYamlMap(): YamlMap 
    {
        return $this->yamlMap;
    }

    /**
     * @param YamlMap $yamlMap
     * @return MapperInterface 
     */
    public function setYamlMap(YamlMap $yamlMap): MapperInterface 
    {
        $this->yamlMap = $yamlMap;

        return $this;
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