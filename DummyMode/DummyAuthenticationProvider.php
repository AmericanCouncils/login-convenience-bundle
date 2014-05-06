<?php

namespace AC\LoginConvenienceBundle\DummyMode;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class DummyAuthenticationProvider extends DaoAuthenticationProvider
{
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        // Do nothing, always approve authentication
    }
}
