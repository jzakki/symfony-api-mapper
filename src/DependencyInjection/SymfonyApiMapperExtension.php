<?php

declare(strict_types=1);

namespace SymfonyApiMapper\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SymfonyApiMapperExtension extends Extension
{
    /**
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load the bundle's service declarations 
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__,2).'/config'));
        $loader->load('services.yaml');
    }
}