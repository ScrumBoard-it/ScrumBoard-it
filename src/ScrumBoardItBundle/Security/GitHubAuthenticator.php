<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Services\ApiCaller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Github Authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class GitHubAuthenticator extends ApiAuthenticator
{

    /**
     * Api configuration.
     * @var array
     */
    private $config;

    public function __construct(Router $router, ApiCaller $apiCaller, TokenStorage $token, array $config = null)
    {
        parent::__construct($router, $apiCaller, $token);
        $this->config = $config;
    }
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $credentials['username'];
        $password = $credentials['password'];
        $user->setHash("$login:$password");

        $url = $this->config['host'].'user';
        $results = $this->apiCaller->call($user, $url);

        if ($results['http_code'] === 200 && !empty($results['content'])) {
            /* $data = $results['content'];
            $user->setEmail($data->email);
            $user->setDisplayName($data->name);
            $user->setImgUrl($data->avatar_url); */
            $user->setApi($this->getApi().'.api');

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getApi()
    {
        return 'github';
    }
}
