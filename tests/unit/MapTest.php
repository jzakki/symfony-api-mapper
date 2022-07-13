<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SymfonyApiMapper\Entity\User;
use SymfonyApiMapper\JsonMapper;
use SymfonyApiMapper\JsonMapperFactory;
use SymfonyApiMapper\XmlMapper;
use SymfonyApiMapper\XmlMapperFactory;

class MapTest extends TestCase
{
    public function testCanMapToObject()
    {
        $mapperFactory = new JsonMapperFactory();
        $mapper = $mapperFactory->create();
        $object = new User();

        $mapper->map(json_decode('{ "name": "John Doe" }'), $object);

        $this->assertInstanceOf(JsonMapper::class, $mapper);
    }
}