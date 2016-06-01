<?php
namespace ScrumBoardItBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Description of user
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * Username
     * @var string
     */
    private $username;

    /**
     * Email
     * @var string
     */
    private $email;

    /**
     * Display Name
     * @var string
     */
    private $displayName;

    /**
     * Img Url
     * @var string
     */
    private $imgUrl;

    /**
     * Connector
     * @var string
     */
    private $connector;

    /**
     * Salt
     * @var string
     */
    private $salt;

    /**
     * Roles
     * @var array
     */
    private $roles;

    /**
     * Hash
     * @var string
     */
    private $hash;

    public function __construct($username, array $roles)
    {
        $this->username = $username;
        $this->roles = $roles;
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
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Roles setter
     * @param array $roles
     * @return self;
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Salt setter
     * @param string $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Username setter
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        
        return $this;
    }

    /**
     * Email getter
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Email setter
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }

    /**
     * Display Name getter
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * DisplayName setter
     * @param string $displayName
     * @return self
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        
        return $this;
    }

    /**
     * Img Url getter
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Img Url setter
     * @param string $imgUrl
     * @return self
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;
        
        return $this;
    }

    /**
     * Connector getter
     *
     * @return string
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * Connector setter
     * @param string $connector
     * @return self
     */
    public function setApi($connector)
    {
        $this->connector = $connector;
        
        return $this;
    }

    /**
     * Hash setter
     * @param string $hash
     * @return self
     */
    public function setHash($hash)
    {
        $this->hash = base64_encode($hash);
        
        return $this;
    }

    /**
     * Hash getter
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * {@inheritDoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (! $user instanceof User) {
            return false;
        }
        
        if ($this->salt !== $user->getSalt()) {
            return false;
        }
        
        if ($this->username !== $user->getUsername()) {
            return false;
        }
        
        if ($this->hash !== $user->getHash()) {
            return false;
        }
        
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        // Mot de passe encodé dans le hash
    }
}
