<?php

namespace AC\LoginConvenienceBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Fp\OpenIdBundle\Controller\SecurityController as BaseSecurityController;

class SecurityController extends BaseSecurityController
{
    # TODO: Use onLogoutSuccess from the security handler instead
    public function logoutAction()
    {
        $this->container->get('security.context')->setToken(null);
        $this->container->get('request')->getSession()->invalidate();

        $response = new Response(json_encode(['logged_out' => true]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
