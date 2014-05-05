<?php

namespace AC\LoginConvenienceBundle\DevMode;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

class DummyAuthenticationProvider extends DaoAuthenticationProvider
{
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        // Do nothing
    }
}
