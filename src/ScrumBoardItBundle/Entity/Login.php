<?php

namespace ScrumBoardItBundle\Entity;

/**
 * Description of login.
 *
 * @author Antony Pradel <antony.pradel@canaltp.fr>
 */
class Login
{
    /**
     * Username.
     *
     * @var string
     */
    private $username;

    /**
     * Password.
     *
     * @var password
     */
    private $password;

    /**
     * Api.
     *
     * @var string
     */
    private $api;

    /**
     * Remember Me.
     *
     * @var bool
     */
    private $rememberMe = false;

    /**
     * Username getter.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Password getter.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Remember Me getter.
     *
     * @return bool
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * Username setter.
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
     * Password setter.
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
     * Remember Me setter.
     *
     * @param bool $rememberMe
     *
     * @return self
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;

        return $this;
    }
}
