<?php

namespace FluffyFactory\Bundle\UserStatsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('fluffy_user_stats');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('user')
                    ->children()
                        ->scalarNode('entity')->defaultValue('User')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}