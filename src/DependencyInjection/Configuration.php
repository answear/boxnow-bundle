<?php

declare(strict_types=1);

namespace Answear\BoxNowBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('answear_box_now');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('clientId')->isRequired()->cannotBeEmpty()->end()
                ?->scalarNode('clientSecret')->isRequired()->cannotBeEmpty()->end()
                ?->scalarNode('apiUrl')->defaultNull()->end()
                ?->scalarNode('logger')->defaultNull()->end()
            ?->end();

        return $treeBuilder;
    }
}
