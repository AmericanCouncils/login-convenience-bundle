<?php

namespace AC\LoginConvenienceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User implements UserInterface, EquatableInterface
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

    public function getRoles() { return ['ROLE_USER']; }
    public function getUsername() { return $this->email; }

    public function getPassword() {
        throw new AuthenticationException("Password is not kept here");
    }

    public function getSalt() {
        throw new AuthenticationException("Salt is not kept here");
    }

    public function eraseCredentials() {
        // Do nothing
    }

    public function isEqualTo(UserInterface $user) {
        return (
            is_a($user, get_class($this)) &&
            $this->email == $user->getEmail()
        );
    }

    public function isAccountNonExpired() {
        return !($this->expiration && $this->expiration >= time());
    }

    public function isAccountNonLocked() {
        return !$this->isLocked();
    }

    public function isCredentialsNonExpired() { return true; }
    public function isEnabled() { return true; }
}
