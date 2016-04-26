<?php
namespace ScrumBoardItBundle\Jira;

abstract class AbstractApi {
    private $user;
    private $data;
    
    public function __construct($token, $data) {
        $this->user = $token->getToken()->getUser();
        $this->data = $data;
    }
    
    protected function getUser() {
        return $this->user;
    }
    
    protected function getData() {
        return $this->data;
    }
    
    protected function curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $this->getUser()->getHash()]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        
        return $content;
    }
    
    public abstract function getProjects();
    public abstract function getIssues($id);
    
    //A implémenter uniquement dans ApiJira
    public abstract function getSprints($id);
}