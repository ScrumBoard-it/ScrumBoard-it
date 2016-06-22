<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Router;

class MainAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderService;

    public function __construct(Router $router, EncoderFactoryInterface $encoderService)
    {
        $this->router = $router;
        $this->encoderService = $encoderService;
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
        $encoder = $this->encoderService->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $credentials['password'], $user->getSalt());
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
