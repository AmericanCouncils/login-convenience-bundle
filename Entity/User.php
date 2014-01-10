<?php

namespace AC\LoginConvenienceBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
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
}
