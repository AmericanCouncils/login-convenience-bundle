<?php

namespace AC\LoginConvenienceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AC\LoginConvenienceBundle\DummyMode\DummySecurityFactory;

class ACLoginConvenienceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $ext = $container->getExtension('security');
        $ext->addSecurityListenerFactory(new DummySecurityFactory);
    }
}
