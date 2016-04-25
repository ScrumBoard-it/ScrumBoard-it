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
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticator extends AbstractGuardAuthenticator {

    private $router;
    private $jira;
    private $rememberme;

    public function __construct(Router $router,  $jira, $rememberme) {
        $this->router = $router;
        $this->jira = $jira;
        $this->rememberme = $rememberme;
    }

    public function getCredentials(Request $request) {
        if ($request->getPathInfo() != '/login_check' || !$request->isMethod('POST')) {
            return;
        }

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
        $user->setHash("$login:$password");
        $url = $this->jira['host'] . $this->jira['rest'] . $login;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $user->getHash()]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && !empty($content)) {
            $data = json_decode($content, true);
            $user->setEmail($data['emailAddress']);
            $user->setDisplayName($data['displayName']);
            $user->setImgUrl($data['avatarUrls']['24x24']);
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $exception,
            'message' => "Nom d'utilisateur ou mot de passe incorrect"
        ));
        
        return new RedirectResponse($this->router->generate('login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        return new RedirectResponse($this->router->generate('home'));
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $authException,
            'message' => "Authentification nécessaire"
        ));
        
        return new RedirectResponse($this->router->generate('login'));
    }

    public function supportsRememberMe() {
        //Passer à true pour activer le remember_me
        return true;
    }
}
