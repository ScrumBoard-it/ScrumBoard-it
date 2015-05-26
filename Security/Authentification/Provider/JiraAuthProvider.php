<?php
namespace CanalTP\ScrumBoardItBundle\Security\Authentification\Provider;
use CanalTP\ScrumBoardItBundle\Jira\User\JiraRest;
use CanalTP\ScrumBoardItBundle\Entity\User;
use CanalTP\ScrumBoardItBundle\Security\Authentification\Token\JiraToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;




class JiraAuthProvider implements AuthenticationProviderInterface {
    
    private $userProvider;
    private $jiraRest;
    
    public function __construct(UserProviderInterface $userProvider, $providerKey, JiraRest $jiraRest)
    {
        $this->userProvider = $userProvider;
        $this->jiraRest = $jiraRest;
    }
    public function supports(TokenInterface $token)
    {        
        return $token instanceof JiraToken;
    }
    public function authenticate(TokenInterface $token)
    {   
        $user = $this->checkUserAuthentication($token);
        $token->setUser($user);
        $token->setAuthenticated(true);
        return $token;
    }
    public function checkUserAuthentication(JiraToken $token)
    {
        $userInfo = $this->jiraRest->getUserInfo($token->getJiraUsername(), $token->getJiraPassword());
        $user = new User();
        $user->setUsername($token->getJiraUsername());
        $user->setBase64Hash($token->getJiraUsername() . ':' . $token->getJiraPassword());
        $user->setRoles('ROLE_USER');
        if ($userInfo){ 
            $user->setEmail($userInfo->emailAddress);
            return $user;
        }
        throw new AuthenticationException( 'Incorrect login and/or password' );
    }
}
