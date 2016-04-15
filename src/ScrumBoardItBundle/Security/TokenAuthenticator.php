<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticator extends AbstractGuardAuthenticator {
    
    private $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getCredentials(Request $request) {
        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            return array(
                'username' => $request->request->get('_username'),
                'password' => $request->request->get('_password'),
            );
        }

        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $login = $user->getUsername();
        $password = $user->getPassword();
        $hash = $user->setHash("$login:$password");
        $password = $credentials['password'];
        $request = $this->getJiraUrl().'rest/api/2/user?username='. $login;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request);
        curl_setopt($curl, CURLOPT_USERPWD, $hash);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        return new Response($exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        
    }

    public function supportsRememberMe() {
        return false;
    }

}
