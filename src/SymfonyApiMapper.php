<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

class SymfonyApiMapper
{

    private string $mapDir;

    /** @param string $mapDir */
    public function __construct(String $mapDir)
    {
        $this->$mapDir = $mapDir;         
    }

    /** @return MapperInterface */
    public function createJsonMapper(): MapperInterface
    {
        $jsonMapperFactory = new JsonMapperFactory();

        return $jsonMapperFactory->create();
    }

    /** @return MapperInterface */
    public function createXmlMapper(): MapperInterface
    {
        $xmlMapperFactory = new XmlMapperFactory();

        return $xmlMapperFactory->create();
    }

}