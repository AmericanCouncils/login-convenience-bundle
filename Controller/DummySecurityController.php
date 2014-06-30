<?php

namespace AC\LoginConvenienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DummySecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $userService = $this->container->getParameter('ac_login_convenience.db_persistence_service');
        $userCls = $this->container->getParameter('ac_login_convenience.user_model_class');
        $userRepo = $this->get($userService)->getRepository($userCls);
        $users = $userRepo->findBy([], null, 50);

        return $this->render('ACLoginConvenienceBundle:Auth:dummyLogin.html.twig', [
            'users' => $users,
            'userClass' => $userCls,
            'targetUrl' => $request->get('target_url'),
            'key' => $request->get('key')
        ]);
    }
}
