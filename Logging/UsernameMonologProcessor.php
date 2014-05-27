<?php

namespace AC\LoginConvenienceBundle\Logging;

class UsernameMonologProcessor
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(array $record)
    {
        $userEmail = "(N/A)";
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        if ($token) {
            $user = $token->getUser();
            if (is_object($user)) {
                $userEmail = $user->getEmail();
            }
        }
        $record['extra']['user'] = $userEmail;
        return $record;
    }
}
