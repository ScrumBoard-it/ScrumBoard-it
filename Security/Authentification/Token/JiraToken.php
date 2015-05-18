<?php
namespace CanalTP\ScrumBoardItBundle\Security\Authentification\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class JiraToken extends AbstractToken
{
    protected $jira_username;
    protected $jira_password;

    public function __construct($username, $password, array $roles = array('ROLE_USER'))
    {
        parent::__construct($roles);
        
        $this->setUser($username);
        $this->jira_username = $username;
        $this->jira_password = $password;
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getJiraUsername()
    {
        return $this->jira_username;
    }

    public function setJiraUsername($jira_login)
    {
        $this->jira_username = $jira_login;
    }

    public function getJiraPassword()
    {
        return $this->jira_password;
    }

    public function setJiraPassword($jira_password)
    {
        $this->jira_password = $jira_password;
    }

    public function serialize()
    {
        return serialize(array($this->jira_username, $this->jira_password, parent::serialize()));
    }

    public function unserialize($serialized)
    {
        list($this->jira_username, $this->jira_password, $parent_data) = unserialize($serialized);
        parent::unserialize($parent_data);
    }

    public  function getCredentials()
    {
        return '';
    }
}
