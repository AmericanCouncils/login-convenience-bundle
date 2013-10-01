<?php

namespace AC\OpenIdConvenienceBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('ac_open_id_convenience')
            ->children()
                ->arrayNode("trusted_providers")
                    ->defaultValue([])
                ->end()
                ->scalarNode("user_provider")
                    ->defaultValue("security.user.provider.default")
                ->end()
            ->end();

        return $treeBuilder;
    }
}
