<?php
namespace ScrumBoardItBundle\Services;

use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiCaller
{
    /**
     * Return a array of the API response
     *
     * @param string $url            
     * @return \stdClass
     */
    public function call(UserInterface $user, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $user->getHash(),
            'Accept: application/json',
            'User-Agent: ScrumBoard-It'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            'content' => json_decode($content),
            'http_code' => $httpCode
        );
    }

    /**
     * 
     * @param unknown $url
     * @param unknown $content
     * @param unknown $nbArguments
     */
    public function send(UserInterface $user, $url, $content, $nbArguments)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $user->getHash(),
            'Accept: application/json',
            'User-Agent: ScrumBoard-It'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, $nbArguments);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
}