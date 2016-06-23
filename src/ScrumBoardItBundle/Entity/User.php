<?php

namespace ScrumBoardItBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * User.
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ScrumBoardItBundle\Repository\SbiUserRepository")
 */
class User implements UserInterface, EquatableInterface
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
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="jiraUrl", type="string", length=255, nullable=true)
     */
    private $jiraUrl;

    /**
     * @var string
     */
    private $api;

    public function __construct()
    {
        $this->roles = array(
            'IS_AUTHENTICATED_FULLY',
        );
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set plainPassword.
     *
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return;
    }

    /**
     * Set jiraUrl.
     *
     * @param string $jiraUrl
     *
     * @return self
     */
    public function setJiraUrl($jiraUrl)
    {
        $this->jiraUrl = $jiraUrl;

        return $this;
    }

    /**
     * Get jiraUrl.
     *
     * @return string
     */
    public function getJiraUrl()
    {
        return $this->jiraUrl;
    }

    /**
     * Get api.
     * 
     * @return bool
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Set api.
     *
     * @param bool $authType
     *
     * @return self
     */
    public function setApi($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user->getUsername() !== $this->username) {
            return false;
        }

        if ($user->getPassword() !== $this->password) {
            return false;
        }

        return true;
    }
}
