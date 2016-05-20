<?php
namespace ScrumBoardItBundle\Security;

use ScrumBoardItBundle\Security\AbstractTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;

class GitHubAuthenticator extends AbstractTokenAuthenticator
{
    /**
     *
     * {@inheritdoc}
     *
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $user->getUsername();
        $password = $credentials['password'];
        $user->setHash("$login:$password");
        $url = $this->data['host'] . 'user';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $user->getHash() .
            "\naccept: application/json
            \naccept-language: en-US,en;q=0.8
            \nuser-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/49.0.2623.108 Chrome/49.0.2623.108 Safari/537.36"
            
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode == 200 && ! empty($content)) {
            $data = json_decode($content);
            $user->setEmail($data->email);
            $user->setDisplayName($data->name);
            $user->setImgUrl($data->avatar_url);
            $user->setApi($this->getApi());
            return true;
        }
        return false;
    }
    
    protected function getApi() {
        return 'github';
    }
}