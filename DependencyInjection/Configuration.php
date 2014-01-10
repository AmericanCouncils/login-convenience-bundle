<?php

namespace AC\LoginConvenienceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ac_login_convenience')
            ->children()
                ->scalarNode("user_class")
                    ->defaultValue("AC\LoginConvenienceBundle\Entity\User")
                ->end()
                ->scalarNode("openid_path")
                    ->defaultNull()
                ->end()
                ->variableNode("secured_paths")
                    ->defaultValue(["/api"])
                ->end()
                ->variableNode("trusted_openid_providers")
                    ->defaultValue([])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
