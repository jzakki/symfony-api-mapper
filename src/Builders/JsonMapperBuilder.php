<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Builders;

use SymfonyApiMapper\Helpers\YamlMap;
use SymfonyApiMapper\Factories\JsonMapper;
use SymfonyApiMapper\Factories\MapperInterface;
use SymfonyApiMapper\Property\JsonPropertyMapper;

class JsonMapperBuilder implements MapperBuilder
{
    /** @var JsonMapper */
    protected $jsonMappper = JsonMapper::class;

    /** @var JsonPropertyMapper */
    protected $propertyMapper;

    /** */
    public function __construct()
    {
        $this->setPropertyMapper(new JsonPropertyMapper);
    }

    /** @return MapperBuilder */
    public static function new(): MapperBuilder
    {
        return new JsonMapperBuilder();
    }

    /**
     * @param YamlMap 
     * @return MapperInterface */
    public function build(YamlMap $yamlMap = null): MapperInterface
    {
        $mapper = new $this->jsonMappper();
        $mapper->setPropertyMapper($this->propertyMapper);
        $mapper->setYamlMap($yamlMap);

        return $mapper;
    }

    /** 
     * @param JsonPropertyMapper $propertyMapper
     * @return JsonMapperBuilder
     */
    public function setPropertyMapper(JsonPropertyMapper $propertyMapper): JsonMapperBuilder
    {
        $this->propertyMapper = $propertyMapper;
        
        return $this;
    }
}