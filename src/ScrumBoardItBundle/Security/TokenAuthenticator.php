<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticator extends AbstractGuardAuthenticator {

    public function getCredentials(Request $request) {
        if ($request->getPathInfo() != '/login_check' || !$request->isMethod('POST')) {
            return;
        }

        /* if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
          return array(
          'username' => $request->request->get('_username'),
          'password' => $request->request->get('_password'),
          );
          } */

        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        dump($credentials, $userProvider);
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $login = $user->getUsername();
        $user->setPassword($credentials['password']);
        $password = $user->getPassword();
        dump($login, $password);
        $url = 'http://jira.canaltp.fr/rest/api/2/user?username=' . $login;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . base64_encode("$login:$password")]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        dump($httpCode);

        return $httpCode == 200 && !empty($content);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        return new Response('<html><body>' . $authException->getMessage() . '</body></html>');
    }

    public function supportsRememberMe() {
        return false;
    }

}
