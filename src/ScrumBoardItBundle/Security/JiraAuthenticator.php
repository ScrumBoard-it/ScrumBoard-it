<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Services\ProfileService;
use ScrumBoardItBundle\Services\ApiCaller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Jira Authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class JiraAuthenticator extends ApiAuthenticator
{
    private $profileService;

    public function __construct(Router $router, ApiCaller $apiCaller, TokenStorage $token, ProfileService $profileService)
    {
        parent::__construct($router, $apiCaller, $token);
        $this->profileService = $profileService;
    }
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $credentials['username'];
        $password = $credentials['password'];
        $user->setHash("$login:$password");

        $url = $this->profileService->getJiraConfiguration($user)->getUrl().'/rest/api/latest/user?username='.$login;
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
