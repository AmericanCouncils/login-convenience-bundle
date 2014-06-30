<?php

namespace AC\LoginConvenienceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FpClassOverrideCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $def = $container->getDefinition('fp_openid.relying_party.default');
        $def->setMethodCalls([]); // Clear all existing RelyingParty appends
        $def->addMethodCall("append", [new Reference('fp_openid.relying_party.recovered_failure')]);
        $def->addMethodCall("append", [new Reference('ac_login_convenience.relying_party.extended')]);
    }
}
