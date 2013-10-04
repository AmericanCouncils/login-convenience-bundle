<?php

namespace AC\LoginConvenienceBundle\Session;

class AuthHeaderSessionStorage extends CookielessSessionStorage
{
    protected function getSessionIdFromRequest($request) {
        $authHead = $request->headers->get("Authorization");
        $matches = [];
        if (preg_match("/^SesID (\w+)$/", $authHead, $matches)) {
            return $matches[1];
        } else {
            return null;
        }
    }

    protected function getSessionName() {
        return "AuthorizationHeader";
    }
}
