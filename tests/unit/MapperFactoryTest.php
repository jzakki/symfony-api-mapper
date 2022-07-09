<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SymfonyApiMapper\JsonMapper;
use SymfonyApiMapper\JsonMapperFactory;
use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\XmlMapperFactory;

class MapperFactoryTest extends TestCase
{
    public function testCanCreateJsonMapper()
    {
        $mapperFactory = new JsonMapperFactory();
        $mapper = $mapperFactory->createMapperInterface();

        $this->assertInstanceOf(JsonMapper::class, $mapper);
    }

    public function testCanCreateXmlMapper()
    {
        $mapperFactory = new XmlMapperFactory();
        $mapper = $mapperFactory->createMapperInterface();

        $this->assertInstanceOf(XmlMapper::class, $mapper);
    }
} 