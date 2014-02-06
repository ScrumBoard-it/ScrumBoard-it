<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $issues = $this->getIssues();
        return $this->render(
            'CanalTPPostitBundle:Default:index.html.twig',
            array(
                'issues' => $issues
            )
        );
    }

    public function exportDataAction(Request $request)
    {
        $jiraTag = $this->container->getParameter('jira_tag');
        $issues = array();
        $addLabel = array();
        $params = $request->request->get("issues");
        foreach ($params as $issue) {
            $response = $this->callApi($issue);
            list($projet, $id) = explode('-', $response->key);
            $response->_projet = $projet;
            if ($response->_projet === "IUS") {
                $response->_projet = "IUSSAAD";
            }
            $response->_id = $id;
            $issues[] = $response;
            if (!in_array($jiraTag, $response->fields->labels)) {
                $addLabel[] = $issue;
            }
        }
        $this->setIssues($addLabel);
        $response = $this->render(
            'CanalTPPostitBundle:Download:index.html.twig',
            array(
                'issues' => $issues
            )
        );

        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition', 'attachment; filename="postit.txt"');

        return $response;
    }

    public function printAction(Request $request)
    {
        $jiraTag = $this->container->getParameter('jira_tag');
        $issues = array();
        $addLabel = array();
        $params = $request->request->get("issues");
        foreach ($params as $issue) {
            $response = $this->callApi($issue);
            list($projet, $id) = explode('-', $response->key);
            $response->_projet = $projet;
            if ($response->_projet === "IUS") {
                $response->_projet = "IUSSAAD";
            }
            $response->_id = $id;
            $issues[] = $response;
            if (!in_array($jiraTag, $response->fields->labels)) {
                $addLabel[] = $issue;
            }
        }
        $this->setIssues($addLabel);
        return $this->render(
            'CanalTPPostitBundle:Print:tickets.html.twig',
            array(
                'issues' => $issues
            )
        );
    }

    public function addLabelAction(Request $request)
    {
        $issues = $request->request->get("issues");
        $this->setIssues($issues);
        return $this->redirect($this->generateUrl('canal_tp_postit_homepage'));
    }

    public function setIssues($issues)
    {
        $jiraTag = $this->container->getParameter('jira_tag');
        foreach ($issues as $url) {
            $options = array(
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => '{"update" : {"labels" : [{"add" : "' . $jiraTag . '"}]}}'
            );
            $result = $this->callApi($url, $options);
        }
    }

    public function getIssues()
    {
        $jiraUrl = $this->container->getParameter('jira_url');
        $sprintId = $this->container->getParameter('sprint_id');
        $result = array();
        $url = $jiraUrl."/rest/api/latest/search?jql=project%20in%20%28SQWAL%2C%20IUS%2C%20CMS%29%20AND%20sprint%20%3D%20" . $sprintId . "%20AND%20%28labels%20is%20EMPTY%20OR%20labels%20!%3D%20post-it%29%20AND%20status%20not%20in%20%28Closed%29&maxResults=1000";
        $notprint = $this->callApi($url);
        if (isset($notprint->issues)) {
            $result['notprint'] = $notprint->issues;
        }
        $url = $jiraUrl."/rest/api/latest/search?jql=project%20in%20%28SQWAL%2C%20IUS%2C%20CMS%29%20AND%20sprint%20%3D%20" . $sprintId . "%20AND%20labels%20%3D%20post-it%20AND%20status%20not%20in%20%28Closed%29&maxResults=1000";
        $print = $this->callApi($url);
        if (isset($print->issues)) {
            $result['print'] = $print->issues;
        }
        return $result;
    }

    public function callApi($url, $options = array())
    {
        $login = $this->container->getParameter('jira_login');
        $password = $this->container->getParameter('jira_password');

        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($login.':'.$password)
        );
        curl_setopt($curl, CURLOPT_URL, $url);
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
