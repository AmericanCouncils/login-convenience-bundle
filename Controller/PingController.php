<?php

namespace AC\LoginConvenienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PingController extends Controller
{
    public function refreshTimeoutPingAction()
    {
        // Don't need to do anything special, the session timeout has been already been reset due
        // to the session being touched by the request.

        $response = new Response(json_encode(['pong' => 'ok']));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
