<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

interface MapperInterface
{
    /**
     * @param $response
     * @param mixed $object 
     * @return string
     */
    public function map($response, $object);
}