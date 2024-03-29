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
                ->booleanNode('user_stat_enabled')
                    ->defaultTrue()
                ->end()
                ->integerNode('user_stat_max_result')
                    ->defaultValue(2000)->min(1)
                ->end()
                ->integerNode('max_month_before_archive')
                    ->defaultValue(6)->min(0)->max(240)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
