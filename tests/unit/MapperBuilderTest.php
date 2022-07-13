<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SymfonyApiMapper\JsonMapper;
use SymfonyApiMapper\JsonMapperBuilder;
use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\XmlMapperBuilder;

class MapperBuilderTest extends TestCase
{
    public function testCanBuildJsonMapper()
    {
        $jsonMapperBuilder = new JsonMapperBuilder();
        $newMapperInterface = $jsonMapperBuilder->build();

        $this->assertInstanceOf(JsonMapper::class, $newMapperInterface);
    }

    public function testCanBuildXmlMapper()
    {
        $xmlMapperBuilder = new XmlMapperBuilder();
        $newMapperBuilder = $xmlMapperBuilder->build();

        $this->assertInstanceOf(XmlMapper::class, $newMapperBuilder);
    }
}