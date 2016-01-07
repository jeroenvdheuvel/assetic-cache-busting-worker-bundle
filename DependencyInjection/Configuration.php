<?php

namespace jvdh\AsseticCacheBustingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jvdh_assetic_cache_busting');

        $rootNode
            ->children()
                ->booleanNode('enabled')->defaultFalse()->end()
                ->booleanNode('enable_cached_worker')->defaultFalse()->end()
                ->scalarNode('separator')->defaultValue('-')->end()
                ->integerNode('hash_length')->defaultValue(8)->end()
                ->scalarNode('hash_algorithm')->defaultValue('sha1')->end()
            ->end();

        return $treeBuilder;
    }
}
