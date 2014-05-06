<?php

namespace AC\LoginConvenienceBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

# Based on:
# http://php-and-symfony.matthiasnoback.nl/2012/01/symfony2-dynamically-add-routes/

class SecurityRoutesLoader implements LoaderInterface
{
    private $loaded = false;
    private $dummyMode;
    private $container;

    public function __construct($dummyMode, $container)
    {
        $this->dummyMode = $dummyMode;
        $this->container = $container;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $resource = "@ACLoginConvenienceBundle/Resources/config/routing.yml";
        if ($this->dummyMode) {
            $resource = str_replace("routing.yml", "routing_dummy.yml", $resource);
        }

        return $this->container->get('routing.loader')->load($resource);
    }

    public function supports($resource, $type = null)
    {
        return 'ac_login_convenience_routes' === $type;
    }

    public function getResolver() {}
    public function setResolver(LoaderResolverInterface $resolver) {}
}
