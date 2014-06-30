<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\HttpFoundation\Response;

class ApiAuthResponseHandler
{
    private $sessionIdPassthrough;

    function __construct(SessionIdPassthrough $e)
    {
        $this->sessionIdPassthrough = $e;
    }

    function response($request, $data)
    {
        $this->sessionIdPassthrough->extractParams($request);
        return $this->sessionIdPassthrough->generateRedirect($data);
    }
}
