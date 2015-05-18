<?php

namespace CanalTP\ScrumBoardItBundle\Jira\User;



class JiraRest {
    private $jiraUrl = null;

   

    public function __construct($jiraUrl){
        $this->jiraUrl = $jiraUrl;

    }
    
    public function getJiraUrl()
    {
        return  $this->jiraUrl;
    }
    public function getUserInfo($username,$password)
    {
        $jiraRequest=$this->getJiraUrl(). 'rest/api/2/user?username=' . $username;
        
       
        
        $curl = curl_init();
        //$login = $this->getJiraLogin();
        $password = $password;
        curl_setopt($curl, CURLOPT_URL,$jiraRequest);
        curl_setopt($curl,CURLOPT_USERPWD,"$username:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_TIMEOUT,10);
        
        
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
        
    }
    
    public function authenticate($login, $password)
    {
        $request=$this->getJiraUrl(). 'rest/api/2/user?username=' . $login;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$request);
        curl_setopt($curl,CURLOPT_USERPWD,"$login:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_TIMEOUT,10);
        
        
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }
    
    /* public function callApi($url, $options = array())
    {
        $login = $this->getJiraLogin();
        $password = $this->getJiraPassword();
        $authorization = base64_encode($login.':'.$password);
        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic '.$authorization
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_TIMEOUT,10);
        foreach ($options as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }*/
}