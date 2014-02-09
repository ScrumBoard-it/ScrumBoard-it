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
            'CanalTPScrumBoardItBundle:Default:index.html.twig',
            array(
                'issues' => $issues
            )
        );
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
            'CanalTPScrumBoardItBundle:Print:tickets.html.twig',
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
        $jira = $this->container->get('canal_tp_scrum_board_it.jira.provider');
        return $jira->getIssues();
    }
}
