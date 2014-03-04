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

    /**
      * @ORM\ManyToOne(targetEntity="AC\LoginConvenienceBundle\Entity\User", fetch="EAGER")
      * @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
      * })
      */
    protected $user;
}
