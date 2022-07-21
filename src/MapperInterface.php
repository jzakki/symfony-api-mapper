<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

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
     * @return YamlMap
     */
    public function getYamlMap():YamlMap;

    /**
     * @param YamlMap $yamlMap
     * @return YamlMap
     */
    public function setYamlMap(YamlMap $yamlMap):MapperInterface;
}