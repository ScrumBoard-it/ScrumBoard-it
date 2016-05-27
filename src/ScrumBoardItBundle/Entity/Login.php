<?php
namespace ScrumBoardItBundle\Entity;

/**
 * Description of login
 * 
 * @author Antony Pradel <antony.pradel@canaltp.fr>
 */
class Login
{
    /**
     * Username
     * @var string
     */
    private $username;
    
    /**
     * Password
     * @var password
     */
    private $password;
    
    /**
     * Api
     * @var string
     */
    private $api;
    
    /**
     * Remember Me
     * @var boolean
     */
    private $rememberMe = false;

    /**
     * Username getter
     * 
     * @return string
     */
    function getUsername()
    {
        return $this->username;
    }

    /**
     * Password getter
     * 
     * @return string
     */
    function getPassword()
    {
        return $this->password;
    }

    /**
     * Api getter
     * 
     * @return string
     */
    function getApi()
    {
        return $this->api;
    }

    /**
     * Remember Me getter
     * 
     * @return boolean
     */
    function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * Username setter
     * @param string $username
     * @return self
     */
    function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Password setter
     * @param string $password
     * @return self
     */
    function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Api setter
     * @param string $api
     * @return self
     */
    function setApi($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * Remember Me setter
     * @param boolean $rememberMe
     * @return self
     */
    function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;

        return $this;
    }
}