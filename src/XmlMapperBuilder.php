<?php

declare(strict_types=1);

namespace SymfonyApiMapper;

use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\MapperInterface;

class XmlMapperBuilder implements MapperBuilder
{
    protected $xmlMappper = XmlMapper::class;

    public static function new(): MapperBuilder
    {
        return new XmlMapperBuilder();
    }

    public function build(): MapperInterface
    {
        return new $this->xmlMappper();
    }

}