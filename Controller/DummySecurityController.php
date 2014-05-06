<?php

namespace AC\LoginConvenienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DummySecurityController extends Controller
{
    public function loginAction()
    {
        $userService = $this->container->getParameter('ac_login_convenience.db_persistence_service');
        $userCls = $this->container->getParameter('ac_login_convenience.user_model_class');
        $userRepo = $this->get($userService)->getRepository($userCls);
        $users = $userRepo->findAll();

        return $this->render(
            'ACLoginConvenienceBundle:Auth:dummyLogin.html.twig',
            [ 'users' => $users, 'userClass' => $userCls ]
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
