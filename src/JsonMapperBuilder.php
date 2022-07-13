<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\JsonMapper;
use SymfonyApiMapper\MapperInterface;
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

    /** @return MapperInterface */
    public function build(): MapperInterface
    {
        $mapper = new $this->jsonMappper();
        $mapper->setPropertyMapper($this->propertyMapper);

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