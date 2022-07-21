<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\Helpers\YamlMap;

class XmlMapperFactory implements MapperFactory
{
    /** @var XmlMapperBuilder */
    private $builder;

    public function __construct(XmlMapperBuilder $builder = null)
    {
        $this->builder = $builder ?? XmlMapperBuilder::new();
    }

    public function create(YamlMap $yamlMap): MapperInterface
    {
        $builder = clone ($this->builder);
        return $builder->build($yamlMap);
    }
}