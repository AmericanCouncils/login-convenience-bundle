<?php

namespace AC\LoginConvenienceBundle\Entity;

use AC\LoginConvenienceBundle\Security\AbstractUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntityUser extends AbstractUser
{
    # FIXME: On deleting User, also delete all associated OpenIdIdentities

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    public function getId() { return $this->id; }

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $email;
    public function getEmail() { return $this->email; }
    public function setEmail($email) { return $this->email = $email; }

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $locked = false;
    public function isLocked() { return $this->locked; }
    public function setLocked($locked) { return $this->locked = $locked; }

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expiration = null;
    public function getExpiration() { return $this->expiration; }
    public function setExpiration($e) { return $this->expiration = $e; }
}
