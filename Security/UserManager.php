<?php

namespace AC\LoginConvenienceBundle\Security;

use Fp\OpenIdBundle\Model\UserManager as BaseUserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use AC\LoginConvenienceBundle\Entity\OpenIdIdentity;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserManager extends BaseUserManager
{
    private $userProvider;
    private $trustedProvider;

    public function __construct(
    IdentityManagerInterface $identityManager,
    UserProviderInterface $userProvider,
    $trustedProviders
    ) {
        parent::__construct($identityManager);

        $this->userProvider = $userProvider;
        $this->trustedProviders = $trustedProviders;
    }

    // This is somewhat misnamed; we aren't going to create a user from an
    // identity, but we may persist this identity and associate it with an
    // existing user if we trust the provider.
    public function createUserFromIdentity($identity, array $attributes = array())
    {
        $trusted = false;
        foreach ($this->trustedProviders as $provider) {
            if (strpos($identity, $provider) === 0) {
                $trusted = true;
                break;
            }
        }
        if (!$trusted) {
            throw new BadCredentialsException("Untrusted identity: $identity");
        }

        if (false === isset($attributes['contact/email'])) {
            throw new BadCredentialsException('No email address provided');
        }
        $email = $attributes['contact/email'];
        $user = $this->userProvider->loadUserByUsername($email);

        $this->associateIdentityWithUser($identity, $user, $attributes);

        return $user;
    }

    public function associateIdentityWithUser($identity, $user, $attributes)
    {
        $openIdIdentity = $this->identityManager->create();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);
        $this->identityManager->update($openIdIdentity);
    }
}
