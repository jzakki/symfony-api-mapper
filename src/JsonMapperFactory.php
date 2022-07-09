<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

class JsonMapperFactory implements MapperFactory
{
    public function createMapperInterface(): MapperInterface
    {
        return new JsonMapper();
    }
}