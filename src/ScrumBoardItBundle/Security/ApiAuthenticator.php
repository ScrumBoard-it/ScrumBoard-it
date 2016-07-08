<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use ScrumBoardItBundle\Services\ApiCaller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

abstract class ApiAuthenticator extends AbstractAuthenticator
{
    /**
     * @var TokenStorage
     */
    private $token;

    /**
     * Api caller service.
     *
     * @var ApiCaller
     */
    protected $apiCaller;

    public function __construct(Router $router, ApiCaller $apiCaller, TokenStorage $token)
    {
        parent::__construct($router);
        $this->apiCaller = $apiCaller;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->token->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        // Check if request comes from the login form and if the requested api is the current one
        if ($request->getPathInfo() == '/bugtracker' && $request->isMethod('POST')) {
            $login = $request->request->get('bugtracker');
            if (!empty($login['api']) && $login['api'] == $this->getApi()) {
                return $login;
            }
        }

        return;
    }

    /**
     * Api name.
     */
    abstract protected function getApi();
}
