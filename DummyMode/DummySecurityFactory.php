<?php

namespace AC\LoginConvenienceBundle\DummyMode;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DummySecurityFactory extends FormLoginFactory
{
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = parent::createAuthProvider($container, $id, $config, $userProviderId);

        // Override the standard DAO provider definition
        $def = $container->getDefinition($provider);
        $def->setClass("AC\LoginConvenienceBundle\DummyMode\DummyAuthenticationProvider");
        $container->setDefinition($provider, $def);

        return $provider;
    }

    public function getKey()
    {
        return 'dummy';
    }
}
