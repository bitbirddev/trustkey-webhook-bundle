<?php

namespace bitbirddev\TrustkeyWebhookBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bitbirddev_trustkey_webhook');
        $rootNode = $treeBuilder->getRootNode();

        // $rootNode
        //     ->children()
        //     ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
        //     ->integerNode('expiration_threshold')->defaultValue(60)->end()
        //     ->end();

        return $treeBuilder;
    }
}
