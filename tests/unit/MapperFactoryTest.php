<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SymfonyApiMapper\JsonMapper;
use SymfonyApiMapper\Factories\JsonMapperFactory;
use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\Factories\XmlMapperFactory;

class MapperFactoryTest extends TestCase
{
    public function testCanCreateJsonMapper()
    {
        $mapperFactory = new JsonMapperFactory();
        $mapper = $mapperFactory->create();

        $this->assertInstanceOf(JsonMapper::class, $mapper);
    }

    public function testCanCreateXmlMapper()
    {
        $mapperFactory = new XmlMapperFactory();
        $mapper = $mapperFactory->create();

        $this->assertInstanceOf(XmlMapper::class, $mapper);
    }
}