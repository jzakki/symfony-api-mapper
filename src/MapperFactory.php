<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Helpers\YamlMap;

interface MapperFactory
{
    /**
     * @return MapperInterface;
     */
    public function create(YamlMap $yamlMap): MapperInterface;
}