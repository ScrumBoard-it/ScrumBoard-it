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

abstract class AbstractTokenAuthenticator extends AbstractGuardAuthenticator
{

    private $router;

    protected $data;

    private $rememberme;
    
    protected $apiCaller;
    
    /**
     *
     * {@inheritdoc}
     *
     */
    public function __construct(Router $router, $data, $rememberme, $ApiCaller)
    {
        $this->router = $router;
        $this->data = $data;
        $this->rememberme = $rememberme;
        $this->apiCaller = $ApiCaller;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getCredentials(Request $request)
    {
        // Check if request comes from the login form and if the requested api is the current one
        if ('/login_check' != $request->getPathInfo() || ! $request->isMethod('POST') || $request->request->get('_api') != $this->getApi()) {
            return;
        }
        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password')
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    protected abstract function getApi();

    /**
     *
     * {@inheritdoc}
     *
     */
    public abstract function checkCredentials($credentials, UserInterface $user);

    /**
     *
     * {@inheritdoc}
     *
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $exception,
            'message' => "Nom d'utilisateur ou mot de passe incorrect"
        ));
        
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, array(
            'exception' => $authException,
            'message' => "Authentification nécessaire"
        ));
        $url = $this->router->generate('login');
        
        return new RedirectResponse($url);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function supportsRememberMe()
    {
        // Passer à true pour activer le remember_me
        return true;
    }
}