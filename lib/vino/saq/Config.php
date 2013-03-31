<?php

namespace vino\saq;

use Symfony\Component\Config\ConfigAbstract;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Config extends ConfigAbstract
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('saq')
            ->children()
                ->arrayNode('availability')
                    ->children()
                        ->scalarNode('displayMinimum')->defaultValue(1)->end()
                        ->scalarNode('posFile')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('soap')
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                        ->arrayNode('options')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}