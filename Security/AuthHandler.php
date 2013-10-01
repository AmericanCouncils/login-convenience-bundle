<?php

namespace AC\OpenIdConvenienceBundle\Security;

use Symfony\Component\HttpFoundation\Response;

class AuthHandler
{
    private $templating;

    function __construct($templating)
    {
        $this->templating = $templating;
    }

    function response($data)
    {
        return new Response($this->templating->render(
            'ACOpenIdConvenienceBundle:Auth:popupReturn.html.twig',
            array('jdata' => json_encode($data))
        ));
    }
}
