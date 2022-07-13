<?php

use SymfonyApiMapper\Entity\User;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SymfonyApiMapper\DependencyInjection\MapperExtention;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new ContainerBuilder();
$extention = new MapperExtention();
$extention->load(array(), $container);
$container->compile();

$mapper = $container->get('app.mapper')->createJsonMapper();

$object = new User();
$mapper->map(json_decode('{ "name": "John Doe" }'), $object);

