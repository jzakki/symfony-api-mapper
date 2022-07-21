<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Helpers\YamlMap;

class JsonMapperFactory implements MapperFactory
{
    /** @var JsonMapperBuilder */
    private $builder;

    public function __construct(JsonMapperBuilder $builder = null)
    {
        $this->builder = $builder ?? JsonMapperBuilder::new();
    }

    public function create(YamlMap $yamlMap): MapperInterface
    {
        $builder = clone ($this->builder);
        return $builder->build($yamlMap);
    }
}