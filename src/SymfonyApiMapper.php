<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Factories\JsonMapperFactory;
use SymfonyApiMapper\Factories\MapperInterface;
use SymfonyApiMapper\Factories\XmlMapperFactory;
use SymfonyApiMapper\Helpers\YamlMap;

class SymfonyApiMapper
{
    /**
     * @var YamlMap
     */
    private $yamlMap;

    public function __construct(YamlMap $yamlMap)
    {
        $this->yamlMap = $yamlMap;
    }

    /** @return MapperInterface */
    public function createJsonMapper(): MapperInterface
    {
        $jsonMapperFactory = new JsonMapperFactory();

        return $jsonMapperFactory->create($this->yamlMap);
    }

    /** @return MapperInterface */
    public function createXmlMapper(): MapperInterface
    {
        $xmlMapperFactory = new XmlMapperFactory();

        return $xmlMapperFactory->create($this->yamlMap);
    }

}