<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

interface MapperInterface
{
    /**
     * @param \stdClass $response
     * @param mixed $object 
     * @return string
     */
    public function map(\stdClass $response, $object);
}