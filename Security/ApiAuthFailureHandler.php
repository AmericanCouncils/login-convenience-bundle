<?php

namespace AC\OpenIdConvenienceBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface as AuthFailureIface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException as AuthEx;
use FOS\RestBundle\View\View;

class ApiAuthFailureHandler extends AuthHandler implements AuthFailureIface
{
    public function onAuthenticationFailure(Request $request, AuthEx $exception)
    {
        return $this->response(array(
            "approved" => false,
            "message" => $exception->getMessage()
        ));
    }
}
