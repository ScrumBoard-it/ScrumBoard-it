<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Services\ApiCaller;

/**
 * Jira Authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class JiraAuthenticator extends ApiAuthenticator
{
    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $credentials['username'];
        $password = $credentials['password'];
        $user->setHash("$login:$password");

        $url = $user->getJiraUrl().'/rest/api/latest/user?username='.$login;
        $results = $this->apiCaller->call($user, $url);

        if ($results['http_code'] === 200 && !empty($results['content'])) {
            /* $content = $results['content'];
            $user->setEmail($content->emailAddress);
            $user->setDisplayName($content->displayName);
            $user->setImgUrl($content->avatarUrls->{'24x24'}); */
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
        return 'jira';
    }
}
