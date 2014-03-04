<?php

namespace AC\LoginConvenienceBundle\Document;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Fp\OpenIdBundle\Document\UserIdentity as BaseUserIdentity;

/**
 * @MongoDB\Document
 */
class OpenIdIdentity extends BaseUserIdentity
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
      * @MongoDB\ReferenceOne(targetEntity="AC\LoginConvenienceBundle\Document\User", simple=true)
      */
    protected $user;
}
