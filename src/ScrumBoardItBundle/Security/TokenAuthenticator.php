<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TokenAuthenticator extends AbstractGuardAuthenticator {
    private $router;
    
    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function getCredentials(Request $request) {
        if ($request->getPathInfo() != '/login_check' || !$request->isMethod('POST')) {
            return;
        }
        
        $this->rememberMe = $request->request->get('_remember_me');
        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $login = $user->getUsername();
        $password = $credentials['password'];
        $user->setHash(base64_encode("$login:$password"));
        $url = 'http://jira.canaltp.fr/rest/api/2/user?username=' . $login;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $user->getHash()]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && !empty($content)) {
            $data = json_decode($content);
            $user->setEmail($data->emailAddress);
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, new AuthenticationException("Nom d'utilisateur ou mot de passe incorrect"));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, new AuthenticationException("Authentification nécessaire"));
        $url = $this->router->generate('login');
        return new RedirectResponse($url);
    }

    public function supportsRememberMe() {
        return false;
    }
}
