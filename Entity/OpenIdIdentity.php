<?php

namespace AC\LoginConvenienceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Fp\OpenIdBundle\Entity\UserIdentity as BaseUserIdentity;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class OpenIdIdentity extends BaseUserIdentity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
      * @var Symfony\Component\Security\Core\User\UserInterface
      *
      * @ORM\ManyToOne(targetEntity="AC\LoginConvenienceBundle\Entity\User", fetch="EAGER")
      * @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
      * })
      */
    protected $user;
}
