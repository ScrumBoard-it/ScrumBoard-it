<?php

namespace ScrumBoardItBundle\Jira\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Jira user.
 */
class JiraUser implements UserInterface
{
    private $username;
    private $password = null;
    private $salt = null;
    private $roles = null;

    public function __construct($username)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function equals(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
