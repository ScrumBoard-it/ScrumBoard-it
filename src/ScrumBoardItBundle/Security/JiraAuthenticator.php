<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Services\ApiCaller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Jira Authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class JiraAuthenticator extends ApiAuthenticator
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Router $router, ApiCaller $apiCaller, TokenStorage $token, Session $session)
    {
        parent::__construct($router, $apiCaller, $token);
        $this->session = $session;
    }
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $credentials['username'];
        $password = $credentials['password'];
        $user->setHash("$login:$password");

        $url = $this->session->get(('user_configuration'))['jira']->getUrl().'/rest/api/latest/user?username='.$login;
        try {
            $results = $this->apiCaller->call($user, $url);
        } catch (\Exception $e) {
            return false;
        }

        if (!empty($results['content'])) {
            $content = $results['content'];
            $user->setEmail($content->emailAddress);
            $user->setDisplayName($content->displayName);
            $user->setImgUrl($content->avatarUrls->{'24x24'});
            $user->setApi($this->getApi().'.api');
            $user->addRole('IS_CONFIGURED');

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getApi()
    {
        return 'jira';
    }
}
