<?php

namespace CanalTP\ScrumBoardItBundle\IssuesProvider;

use CanalTP\ScrumBoardItBundle\Processor\ApiProcessorInterface;

/**
 * Description of JiraIssuesProvider
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraIssuesProvider implements IssuesProviderInterface {
    private $host;
    private $login;
    private $password;
    private $sprint;
    /** @var $searchProcessor CanalTP\ScrumBoardItBundle\Processor\JiraSearchProcessor **/
    private $searchProcessor;

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

    public function getIssue($url)
    {
        $result = $this->callApi($url);
        
        $processor = $this->getSearchProcessor();
        return $processor->handle($result);
    }
    
    public function getIssues()
    {
        $url = "/rest/api/latest/search?jql=Sprint%20%3D%20".$this->getSprint()."%20AND%20status%20not%20in%20%28Closed%29&maxResults=1000";
        $result = $this->callApi($url);
        $processor = $this->getSearchProcessor();
        return $processor->handle($result);
    }

    public function getSearchProcessor() {
        return $this->searchProcessor;
    }

    public function setSearchProcessor(ApiProcessorInterface $searchProcessor) {
        $this->searchProcessor = $searchProcessor;
        return $this;
    }

    public function callApi($url, $options = array())
    {
        $authorization = base64_encode($this->login.':'.$this->password);
        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic '.$authorization
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        foreach ($options as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }
}
