<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Builders;

use SymfonyApiMapper\Helpers\YamlMap;
use SymfonyApiMapper\Factories\XmlMapper;
use SymfonyApiMapper\Factories\MapperInterface;
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

    /** 
     * @param YamlMap
     * @return MapperInterface */
    public function build(YamlMap $yamlMap = null): MapperInterface
    {
        $mapper = new $this->xmlMappper();
        $mapper->setPropertyMapper($this->propertyMapper);
        $mapper->setYamlMap($yamlMap);

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