<?php

namespace AC\SecureResourceAuthBundle\Security;

use Fp\OpenIdBundle\Model\UserManager as BaseUserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Doctrine\ORM\EntityManager;
use AC\SecureResourceAuthBundle\Entity\OpenIdIdentity;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserManager extends BaseUserManager
{
    private $trustedProvider;

    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager, $trustedProvider)
    {
        parent::__construct($identityManager);

        $this->entityManager = $entityManager;
        $this->trustedProvider = $trustedProvider;
    }

    public function createUserFromIdentity($identity, array $attributes = array())
    {
        if (strpos($identity, $this->trustedProvider) !== 0) {
            throw new BadCredentialsException("Untrusted identity: $identity, need " . $this->trustedProvider);
        }

        if (false === isset($attributes['contact/email'])) {
            throw new BadCredentialsException('No email address provided');
        }

        $repo = $this->entityManager->getRepository('ACSecureResourceAuthBundle:User');
        $user = $repo->findOneBy([
            'email' => $attributes['contact/email']
        ]);
        if (!$user) {
            throw new BadCredentialsException(
                'User not authorized in this app: ' . $attributes['contact/email']
            );
        }

        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);
        $this->entityManager->persist($openIdIdentity);
        $this->entityManager->flush();

        return $user;
    }
}
