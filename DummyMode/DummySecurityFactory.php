<?php

namespace AC\LoginConvenienceBundle\DevMode;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\Definition;

class DummySecurityFactory extends FormLoginFactory
{
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = parent::createAuthProvider($container, $id, $config, $userProviderId);
        $origDef = $container->getDefinition($provider);

        $container->setDefinition($provider, new Definition(
            "AC\LoginConvenienceBundle\DevMode\DummyAuthenticationProvider",
            $origDef->getArguments()
        ));
    }

    public function getKey()
    {
        return 'dummy';
    }
}
