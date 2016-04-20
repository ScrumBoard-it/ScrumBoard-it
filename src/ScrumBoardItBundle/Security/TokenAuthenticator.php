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
    private $jira;

    public function __construct(Router $router,  $jira) {
        $this->router = $router;
        $this->jira = $jira;
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
        dump($this->jira);
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
            $data = json_decode($content);
            $user->setEmail($data->emailAddress);
            return true;
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $exception,
            'message' => "Nom d'utilisateur ou mot de passe incorrect"
        ));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        //Return response required for remember_me
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $authException,
            'message' => "Authentification nécessaire"
        ));
        $url = $this->router->generate('login');
        return new RedirectResponse($url);
    }

    public function supportsRememberMe() {
        //Passer à true pour activer le remember_me
        return false;
    }
}
