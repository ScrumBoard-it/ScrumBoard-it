<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Router;

class SbiAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * Router.
     * 
     * @var Router
     */
    private $router;
    
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        // Check if request comes from the login form
        if ($request->getPathInfo() == '/login' && $request->isMethod('POST')) {
            $login = $request->request->get('login');
            
            return $login;
        }
    
        return;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $credentials['password'] === $user->getPassword();
    }
    
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $exception,
            'message' => "Nom d'utilisateur ou mot de passe incorrect",
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $authException,
            'message' => 'Authentification nécessaire',
        ));
    
        return new RedirectResponse($this->router->generate('login'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }
}