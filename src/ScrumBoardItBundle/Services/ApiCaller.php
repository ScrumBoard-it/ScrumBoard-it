<?php
namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class ApiCaller
{

    /**
     * Return a array of the API response
     *
     * @param string $url            
     * @return \stdClass
     */
    public function call(User $user, $url)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . $user->getHash(),
                'Accept' => 'application/json',
                'User-Agent' => ': ScrumBoard-It'
            ]
        ]);
        $response = $client->get($url);
        
        $links = Psr7\parse_header($response->getHeader('Link'));
        $content = json_decode($response->getBody());
        $httpCode = $response->getStatusCode();
        
        return array(
            'links' => $links,
            'content' => $content,
            'http_code' => $httpCode
        );
    }

    /**
     *
     * @param string $url            
     * @param array $content            
     * @param int $nbArguments            
     */
    public function send(User $user, $url, $content, $nbArguments)
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
}