<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface as AuthFailureIface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException as AuthEx;
use FOS\RestBundle\View\View;

class ApiAuthFailureHandler extends ApiAuthResponseHandler implements AuthFailureIface
{
    public function onAuthenticationFailure(Request $request, AuthEx $exception)
    {
        return $this->response($request, [
            "approved" => false,
            "message" => $exception->getMessage()
        ]);
    }
}
