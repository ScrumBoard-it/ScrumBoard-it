<?php

namespace CanalTP\ScrumBoardItBundle\IssuesProvider;

/**
 * Description of JiraIssuesProvider
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraIssuesProvider implements IssuesProviderInterface {
    private $host;
    private $login;
    private $password;
    private $sprint;
    
    public function __construct($host, $login, $password)
    {
        $this->host = $host;
        $this->login = $login;
        $this->password = $password;
    }

    public function getSprint()
    {
        return $this->sprint;
    }

    public function setSprint($sprint)
    {
        $this->sprint = $sprint;
        return $this;
    }

    public function getIssues()
    {
        $result = array();
        $url = "/rest/api/latest/search?jql=project%20in%20%28SQWAL%2C%20IUS%2C%20CMS%29%20AND%20sprint%20%3D%20" . $this->getSprint() . "%20AND%20%28labels%20is%20EMPTY%20OR%20labels%20!%3D%20post-it%29%20AND%20status%20not%20in%20%28Closed%29&maxResults=1000";
        $notprint = $this->callApi($url);
        if (isset($notprint->issues)) {
            $result['notprint'] = $notprint->issues;
        }
        $url = "/rest/api/latest/search?jql=project%20in%20%28SQWAL%2C%20IUS%2C%20CMS%29%20AND%20sprint%20%3D%20" . $this->getSprint() . "%20AND%20labels%20%3D%20post-it%20AND%20status%20not%20in%20%28Closed%29&maxResults=1000";
        $print = $this->callApi($url);
        if (isset($print->issues)) {
            $result['print'] = $print->issues;
        }
        return $result;
    }

    private function callApi($url, $options = array())
    {
        $authorization = base64_encode($this->login.':'.$this->password);
        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic '.$authorization
        );
        curl_setopt($curl, CURLOPT_URL, $this->host.$url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach ($options as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
        $issues = curl_exec($curl);
        curl_close($curl);
        return json_decode($issues);
    }
}
