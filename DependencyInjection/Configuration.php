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
                ->arrayNode('exclude_route')
                    ->scalarPrototype()->end()
                ->end()
                ->integerNode('max_month_before_archive')
                    ->defaultValue(6)->min(0)->max(240)
                ->end()
                ->booleanNode('user_stat_enabled')
                    ->defaultTrue()
                ->end()
                ->booleanNode('archive_enabled')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
