<?php

namespace ScrumBoardItBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Symfony user.
 */
class User implements UserInterface, EquatableInterface
{
    private $username;
    private $password;
    private $email;
    private $salt;
    private $roles;
    private $hash;

    public function __construct($username, array $roles)
    {
        $this->username = $username;
        $this->roles = $roles;
    }
    public function eraseCredentials()
    {
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    
    public function getBase64Hash()
    {
        return $this->hash;
    }
    public function setBase64Hash($data)
    {
        $this->hash = base64_encode($data);
    }
    
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
