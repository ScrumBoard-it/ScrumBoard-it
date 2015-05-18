<?php
namespace CanalTP\ScrumBoardItBundle\Jira\User;
use CanalTP\ScrumBoardItBundle\Jira\User\JiraRest;
use CanalTP\ScrumBoardItBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;


class JiraUserProvider implements UserProviderInterface {

    private $jiraRest;
     private $container;

    public function __construct(JiraRest $jiraRest, Container $container){
        $this->jiraRest = $jiraRest;
        $this->container=$container;
        
    }

    public function loadUserByUsername($username)
    {
       $request=$this->container->get('request');
       $password=$request->get('_password');
    
        $userData = $this->jiraRest->getUserInfo($username, $password);        

        if ($userData)
        {
            return new JiraUser($username);
        }


        throw new UsernameNotFoundException("KO");
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $decodedUserData = base64_decode($user->getBase64Hash());
        list($username, $password) = explode(':', $decodedUserData);
        $userInfoResponse = $this->jiraRest->getUserInfo($username, $password);
        $userInfo = json_decode($userInfoResponse->getContent());

        $user = new User();
        $user->setUsername($user->getUsername());
        $user->setEmail($userInfo->emailAddress);
        $user->setBase64Hash($user->getBase64Hash());
        $user->setRoles('ROLE_USER');
        return $user;
    }    
     
     public function supportsClass($class)
    {
        return $class === 'CanalTP\ScrumBoardBundle\Entity\User';
    }
}