<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

class SymfonyApiMapper
{

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