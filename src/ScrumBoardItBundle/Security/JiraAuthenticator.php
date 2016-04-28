<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Security\AbstractTokenAuthenticator;

class JiraAuthenticator extends AbstractTokenAuthenticator {

    public function checkCredentials($credentials, UserInterface $user) {
        $login = $user->getUsername();
        $password = $credentials['password'];
        $user->setHash("$login:$password");
        $url = $this->data['host'] . $this->data['api'] . 'user?username=' . $login;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $user->getHash()]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode == 200 && !empty($content)) {
            $data = json_decode($content, true);
            $user->setEmail($data['emailAddress']);
            $user->setDisplayName($data['displayName']);
            $user->setImgUrl($data['avatarUrls']['24x24']);
            $user->setApi('jira');
            return true;
        }
        return false;
    }
}
