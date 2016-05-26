<?php
namespace ScrumBoardItBundle\Entity;

class Login
{
    private $username;
    private $password;
    private $api;
    private $rememberMe = false;

    function getUsername()
    {
        return $this->username;
    }

    function getPassword()
    {
        return $this->password;
    }

    function getApi()
    {
        return $this->api;
    }

    function getRememberMe()
    {
        return $this->rememberMe;
    }

    function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    function setApi($api)
    {
        $this->api = $api;

        return $this;
    }

    function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;

        return $this;
    }
}