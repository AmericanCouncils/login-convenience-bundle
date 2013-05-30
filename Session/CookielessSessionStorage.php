<?php

namespace AC\SecureResourceAuthBundle\Session;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

abstract class CookielessSessionStorage extends NativeSessionStorage
{
    private $container;

    public function __construct($container, $handler = null, $metaBag = null)
    {
        $this->container = $container;

        // FIXME: This is a hack to deal with hard-coded cookie requirement
        // in Request::hasPreviousSession. Since we're just altering the
        // Request here, no actual cookies will be sent to the client.
        $request = $this->container->get('request');
        $request->cookies->set($this->getSessionName(), "1");

        $opts = [
            "use_cookies" => 0,
            "use_only_cookies" => 0
        ];
        parent::__construct($opts, $handler, $metaBag);

        $this->setName($this->getSessionName());
    }

    abstract protected function getSessionIdFromRequest($request);
    abstract protected function getSessionName();

    public function start()
    {
        if ($this->started && !$this->closed) {
            return true;
        }

        $request = $this->container->get('request');
        $sesId = $this->getSessionIdFromRequest($request);
        if ($sesId) {
            $this->setId($sesId);
        }

        return parent::start();
    }
}
