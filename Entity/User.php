<?php
/*
 * 
 */

namespace CanalTP\ScrumBoardItBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{

    private $username;

    private $salt;

    private $password;

    private $email;
    
    private $roles;
    
    public function __construct()
    {
        $this->roles=('ROLE_USER');
    }
    public function eraseCredentials() 
    {    
    }
    public function getPassword() 
    {
      return $this->password;
    }
     public function setPassword($password) 
    {
        $this->password = $password;
        return $this;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles($roles)
    {
        $this->roles=$roles;
    }
    public function getSalt() 
    {
        return $this->salt;
        
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    public function getUsername()
    {
      return $this->username;  
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    public function getEmail()
    {
        return $this->email;
    }
     public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
}