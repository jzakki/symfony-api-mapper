<?php 

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Exception\TypeException;
use SymfonyApiMapper\Property\PropertyMap;
use SymfonyApiMapper\Wrapper\ObjectWrapper;

class XmlMapper implements MapperInterface
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
     * @param $xml
     * @param mixed $object
     * @return string
     */
    public function map($xml, $object)
    {
        $xml = json_decode(json_encode(simplexml_load_string($xml)));

        if(! \is_object($object)) {
            throw TypeException::forArgument(__METHOD__, 'object', $object, 2, '$object');
        }
        
        $propertyMap = new PropertyMap();
        $handler = $this->resolve();
        $handler($xml, new ObjectWrapper($object), $propertyMap, $this);
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