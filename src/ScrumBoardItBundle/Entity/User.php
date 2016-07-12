<?php

namespace ScrumBoardItBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * User.
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
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
     */
    private $roles = array();

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/\S*\/$/",
     *     match=true,
     *     message="L'url doit finir par '/'"
     * )
     * @Assert\Url(
     *    protocols = {"http"},
     *    message = "Url invalide"
     * )
     * @ORM\Column(name="jira_url", type="string", length=255, nullable=true)
     */
    private $jiraUrl;

    /**
     * @var number
     *
     * @ORM\Column(name="configuration", type="array", nullable=true)
     */
    private $configuration;

    /**
     * @var string
     */
    private $api;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $imgUrl;

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
     * Add a role.
     *
     * @param string $role
     */
    public function addRole($role)
    {
        array_push($this->roles, $role);
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
     * Set configuration.
     *
     * @param array $configuration
     *
     * @return self
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration.
     *
     * @return array
     */
    public function getConfiguration()
    {
        if (empty($this->configuration)) {
            $this->configuration = array(
                'user_story' => 0,
                'sub_task' => 0,
                'poc' => 0,
            );
        }

        return $this->configuration;
    }

    /**
     * Get api.
     *
     * @return string
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Set api.
     *
     * @param string $api
     *
     * @return self
     */
    public function setApi($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * Get Hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return self
     */
    public function setHash($hash)
    {
        $this->hash = base64_encode($hash);

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get display name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set display name.
     *
     * @param string $displayName
     *
     * @return self
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get img url.
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set img url.
     *
     * @param string $imgUrl
     *
     * @return self
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

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
        if ($user->getUsername() !== $this->username || $user->getPassword() !== $this->password) {
            return false;
        }

        return true;
    }
}
