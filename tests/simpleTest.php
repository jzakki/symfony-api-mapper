<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use SymfonyApiMapper\Entity\User;
use Symfony\Component\ErrorHandler\Debug;
use SymfonyApiMapper\DependencyInjection\MapperExtention;

require_once __DIR__ . '/../vendor/autoload.php';
Debug::enable();

$container = new ContainerBuilder();
$extention = new MapperExtention();
$extention->load(array(), $container);
$container->compile();

//$mapper = $container->get('app.mapper')->createXmlMapper();
$mapper = $container->get('app.mapper')->createJsonMapper();

$object = new User();
$json = '{"name": "John Doe"}';
$xml = '<?xml version="1.0" encoding="UTF-8" ?><root><name>John Doe</name></root>';
$mapper->map($json, $object);

dd($object);

