<?php 

namespace SymfonyApiMapper\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('mapper');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('map')->isRequired()->end()
            ->end();    
            
        return $treeBuilder;    
    }
}