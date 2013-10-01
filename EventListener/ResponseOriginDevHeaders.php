<?php

namespace AC\OpenIdConvenienceBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseOriginDevHeaders
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        # TODO Do this in a secure way!
        $event->getResponse()->headers->set("Access-Control-Allow-Origin", "*");
        $event->getResponse()->headers->set("Access-Control-Allow-Headers",
            $event->getRequest()->headers->get("Access-Control-Request-Headers")
        );
    }
}
