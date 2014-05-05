<?php

namespace AC\LoginConvenienceBundle\DevMode;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DummySecurityController extends Controller
{
    public function loginAction()
    {
        return $this->render(
            'ACLoginConvenienceBundle:Auth:dummyLogin.html.twig',
            [
                'users' => $users
            ]
        );
    }

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
