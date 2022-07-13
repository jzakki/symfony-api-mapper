<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

interface MapperFactory
{
    /**
     * @return MapperInterface;
     */
    public function create(): MapperInterface;
}