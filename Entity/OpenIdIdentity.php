<?php

namespace AC\LoginConvenienceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Fp\OpenIdBundle\Entity\UserIdentity as BaseUserIdentity;

/**
 * @ORM\Entity
 */
class OpenIdIdentity extends BaseUserIdentity
{
    /**
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    // Associated with the correct User class from IdentityUserRelationSubscriber
    protected $user;
}
