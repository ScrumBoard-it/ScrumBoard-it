<?php

namespace CanalTP\ScrumBoardItBundle\Service;

/**
 * Description of AbstractService.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
abstract class AbstractService implements ServiceInterface
{
    private $host;
    private $login;
    private $password;
    private $options;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        $this->setHost($options['host']);
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
