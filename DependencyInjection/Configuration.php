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
                ->scalarNode("user_model_class")
                    ->isRequired()
                ->end()
                ->scalarNode("db_driver")
                    ->defaultValue("orm")
                ->end()
                ->variableNode("secured_paths")
                    ->defaultValue([])
                ->end()
                ->variableNode("trusted_openid_providers")
                    ->defaultValue([])
                ->end()
                ->scalarNode("dummy_mode")
                    ->defaultFalse()
                ->end()
                ->variableNode("api_keys")
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
