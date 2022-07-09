<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

class XmlMapperFactory implements MapperFactory
{
    public function createMapperInterface(): MapperInterface
    {
        return new XmlMapper();
    }
}