<?php

namespace AC\LoginConvenienceBundle\Document;

use AC\LoginConvenienceBundle\Security\AbstractUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\MappedSuperclass
 */
abstract class AbstractDocumentUser extends AbstractUser
{
    # FIXME: On deleting User, also delete all associated OpenIdIdentities

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;
    public function getId() { return $this->id; }

    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     */
    protected $email;
    public function getEmail() { return $this->email; }
    public function setEmail($email) { return $this->email = $email; }

    /**
     * @MongoDB\Boolean
     */
    protected $locked = false;
    public function isLocked() { return $this->locked; }
    public function setLocked($locked) { return $this->locked = $locked; }

    /**
     * @MongoDB\Date
     */
    protected $expiration = null;
    public function getExpiration() { return $this->expiration; }
    public function setExpiration($e) { return $this->expiration = $e; }
}
