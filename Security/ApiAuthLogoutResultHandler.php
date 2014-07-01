<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class ApiAuthLogoutResultHandler implements LogoutSuccessHandlerInterface
{
    public function onLogoutSuccess(Request $request)
    {
        $response = new Response(json_encode(['logged_out' => true]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
