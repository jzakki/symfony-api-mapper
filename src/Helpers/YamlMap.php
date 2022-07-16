<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Helpers;

use Symfony\Component\Yaml\Yaml;

class YamlMap {

    /**
     * @var mixed
     */
    private $map;

    public function __construct()
    {
        $this->map = Yaml::parseFile(dirname(__DIR__,2).'/config/map.yaml');
    }

    /**
     * @param string $entityClassName
     * @param string $propertyName
     * @return string|null
     */
    public function getApiEqualsToName(string $entityClassName, string $propertyName){

        if(! isset($this->map[$entityClassName])) return null;
        if(! array_key_exists($propertyName, $this->map[$entityClassName])) return null;

        return (string)$this->map[$entityClassName][$propertyName];
    }

    /**
     * @param string $entityClassName
     * @param string $propertyName
     * @return string|bool
     */
    public function getApiEqualsToKey(string $entityClassName, string $key){

        if(! isset($this->map[$entityClassName])) return null;
        $result = array_search($key, $this->map[$entityClassName]);
        if(! $result) return null;

        return (string)$result;
    }



}