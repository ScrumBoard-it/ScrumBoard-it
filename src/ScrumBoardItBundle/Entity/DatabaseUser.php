<?php

namespace ScrumBoardItBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatabaseUser
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ScrumBoardItBundle\Repository\DatabaseUserRepository")
 */
class DatabaseUser
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
     * @var string
     *
     * @ORM\Column(name="urlJira", type="string", length=255)
     */
    private $urlJira;


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
     * @return DatabaseUser
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
     * @return DatabaseUser
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
     * Set urlJira
     *
     * @param string $urlJira
     *
     * @return DatabaseUser
     */
    public function setUrlJira($urlJira)
    {
        $this->urlJira = $urlJira;

        return $this;
    }

    /**
     * Get urlJira
     *
     * @return string
     */
    public function getUrlJira()
    {
        return $this->urlJira;
    }
}
