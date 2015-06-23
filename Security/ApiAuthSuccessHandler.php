<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface as AuthSuccessIface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiAuthSuccessHandler extends ApiAuthResponseHandler implements AuthSuccessIface
{
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->response($request, [
            "approved" => true,
            "user" => $token->getUser()->getEmail(),
            "userId" => $token->getUser()->getId(),
            "sessionId" => $request->getSession()->getId()
        ]);
    }
}
