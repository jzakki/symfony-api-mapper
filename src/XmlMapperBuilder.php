<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\MapperInterface;
use SymfonyApiMapper\Property\XmlPropertyMapper;

class XmlMapperBuilder implements MapperBuilder
{
    /** @var XmlMapper */
    protected $xmlMappper = XmlMapper::class;

    /** @var XmlPropertyMapper */
    protected $propertyMapper;

    /** */
    public function __construct()
    {
        $this->setPropertyMapper(new XmlPropertyMapper);
    }

    /** @return MapperBuilder */
    public static function new(): MapperBuilder
    {
        return new XmlMapperBuilder();
    }

    /** @return MapperInterface */
    public function build(): MapperInterface
    {
        $mapper = new $this->xmlMappper();
        $mapper->setPropertyMapper($this->propertyMapper);

        return $mapper;
    }

    /** 
     * @param XmlPropertyMapper $propertyMapper
     * @return XmlMapperBuilder
     */
    public function setPropertyMapper(XmlPropertyMapper $propertyMapper): XmlMapperBuilder
    {
        $this->propertyMapper = $propertyMapper;
        
        return $this;
    }

}