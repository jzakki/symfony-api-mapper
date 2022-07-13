<?php 

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\MapperInterface;

interface MapperBuilder
{
    public static function new(): MapperBuilder;

    public function build(): MapperInterface;
}