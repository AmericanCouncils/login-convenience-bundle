<?php

namespace AC\LoginConvenienceBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

abstract class AbstractUser implements AdvancedUserInterface, EquatableInterface
{
    abstract public function getId();

    abstract public function getEmail();
    abstract public function setEmail($email);

    abstract public function isLocked();
    abstract public function setLocked($locked);

    abstract public function getExpiration();
    abstract public function setExpiration($expiration);

    public function getRoles() { return ['ROLE_USER']; }
    public function getUsername() { return $this->getEmail(); }

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
            $this->getEmail() == $user->getEmail()
        );
    }

    public function isAccountNonExpired() {
        $expr = $this->getExpiration();
        return !(!is_null($expr) && $expr <= time());
    }

    public function isAccountNonLocked() {
        return !$this->isLocked();
    }

    public function isCredentialsNonExpired() { return true; }
    public function isEnabled() { return true; }
}
