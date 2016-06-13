<?php

namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

/**
 * Api Caller.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ApiCaller
{
    /**
     * Return an array of the API response.
     *
     * @param string $url
     *
     * @return \stdClass
     */
    public function call(User $user, $url)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic '.$user->getHash(),
                'Accept' => 'application/json',
                'User-Agent' => ': ScrumBoard-it',
            ],
        ]);
        try {
            $response = $client->get($url);
        } catch (\Exception $e) {
            return;
        }

        $links = Psr7\parse_header($response->getHeader('Link'));
        $content = json_decode($response->getBody());
        $httpCode = $response->getStatusCode();

        return array(
            'links' => $links,
            'content' => $content,
            'http_code' => $httpCode,
        );
    }

    /**
     * Send array to an API.
     *
     * @param string $url
     * @param string $content
     * @param int    $nbArguments
     *
     * @return \stdClass
     */
    public function send(User $user, $url, $content, $nbArguments)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic '.$user->getHash(),
            'Accept: application/json',
            'User-Agent: ScrumBoard-It',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, $nbArguments);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Put message to an API.
     *
     * @param User   $user
     * @param string $url
     * @param string $content
     */
    public function puting(User $user, $url, $content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic '.$user->getHash(),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_exec($ch);
        curl_close($ch);
    }
}
