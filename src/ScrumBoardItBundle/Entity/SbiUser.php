<?php

namespace ScrumBoardItBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * SbiUser
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ScrumBoardItBundle\Repository\SbiUserRepository")
 */
class SbiUser implements UserInterface, EquatableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles;

    /**
     * @var int
     *
     * @ORM\Column(name="salt", type="integer", nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="jiraUrl", type="string", length=255, nullable=true)
     */
    private $jiraUrl;

    public function __construct()
    {
        $this->roles = array(
            'IS_AUTHENTICATED_FULLY'
        );
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return SbiUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return SbiUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return SbiUser
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set salt
     *
     * @param integer $salt
     *
     * @return SbiUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return int
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set jiraUrl
     *
     * @param string $jiraUrl
     *
     * @return SbiUser
     */
    public function setJiraUrl($jiraUrl)
    {
        $this->jiraUrl = $jiraUrl;

        return $this;
    }

    /**
     * Get jiraUrl
     *
     * @return string
     */
    public function getJiraUrl()
    {
        return $this->jiraUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user->getUsername() !== $this->username) {
            return false;
        }
    
        if ($user->getPassword() !== $this->password) {
            return false;
        }
    
        if ($user->getSalt() !== $this->salt) {
            return false;
        }
    
        return true;
    }
}
