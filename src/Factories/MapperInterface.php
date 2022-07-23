<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Factories;

use SymfonyApiMapper\Helpers\YamlMap;

interface MapperInterface
{
    /**
     * @param $response
     * @param mixed $object 
     * @return string
     */
    public function map($response, $object);

    /**
     * @param YamlMap $yamlMap
     * @return MapperInterface
     */
    public function setYamlMap(YamlMap $yamlMap): MapperInterface;

    /**
     * @return YamlMap
     */
    public function getYamlMap(): YamlMap;
}