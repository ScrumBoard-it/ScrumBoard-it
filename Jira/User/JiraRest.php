<?php

namespace CanalTP\ScrumBoardItBundle\Jira\User;

/**
 * Call API jira.
 *
 * @author Blaise stanley <stanleyleob@gmail.com>
 */
class JiraRest
{
    private $jiraUrl = null;

    public function __construct($jiraUrl)
    {
        $this->jiraUrl = $jiraUrl;
    }

    public function getJiraUrl()
    {
        return  $this->jiraUrl;
    }
    public function getUserInfo($username, $password)
    {
        $jiraRequest = $this->getJiraUrl().'rest/api/2/user?username='.$username;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $jiraRequest);
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }

    public function authenticate($login, $password)
    {
        $request = $this->getJiraUrl().'rest/api/2/user?username='.$login;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request);
        curl_setopt($curl, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }
}
