<?php 

declare(strict_types=1);

namespace SymfonyApiMapper\Builders;

use SymfonyApiMapper\Helpers\YamlMap;
use SymfonyApiMapper\Factories\MapperInterface;

interface MapperBuilder
{
    /** @return MapperBuilder */
    public static function new(): MapperBuilder;

    /**
     * @param YamlMap $yamlMap
     * @return MapperInterface 
     */
    public function build(YamlMap $yamlMap): MapperInterface;
}