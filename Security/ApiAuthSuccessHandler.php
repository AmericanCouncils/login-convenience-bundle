<?php

namespace AC\OpenIdConvenienceBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface as AuthSuccessIface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiAuthSuccessHandler extends AuthHandler implements AuthSuccessIface
{
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->response(
            array(
                "approved" => true,
                "user" => $token->getUser()->getEmail(),
                "sessionId" => $request->getSession()->getId()
            )
        );
    }
}
