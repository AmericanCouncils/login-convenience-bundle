<?php

namespace AC\LoginConvenienceBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class OpenIdRouteAdder
{
    private $router;
    private $loader;
    private $oidPath;
    private $kernel;

    public function __construct(Router $router, DelegatingLoader $loader, $oidPath, $kernel)
    {
        $this->router = $router;
        $this->loader = $loader;
        $this->oidPath = $oidPath;
        $this->kernel = $kernel;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $oidRoutes = $this->loader->load($this->kernel->locateResource(
            "@FpOpenIdBundle/Resources/config/routing/security.xml"
        ));
        $oidRoutes->addPrefix($this->oidPath);
        $this->router->getRouteCollection()->addCollection($oidRoutes);
    }
}
