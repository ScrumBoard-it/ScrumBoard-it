<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        return $this->render(
            'CanalTPScrumBoardItBundle:Default:index.html.twig',
            array(
                'issues' => $service->getIssues()
            )
        );
    }

    public function printAction(Request $request)
    {
        $issuesService = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $issues = array();
        $addLabel = array();
        $params = $request->request->get("issues");
        foreach ($params as $issueUrl) {
            $issue = $jira->getIssue($issueUrl);
            $issues[] = $issue;
            if ($issue->isPrinted()) {
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
        $jira = $this->container->get('canal_tp_scrum_board_it.jira.provider');
        $jiraTag = $this->container->getParameter('jira_tag');
        foreach ($issues as $url) {
            $options = array(
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => '{"update" : {"labels" : [{"add" : "' . $jiraTag . '"}]}}'
            );
            $result = $jira->callApi($url, $options);
        }
    }

    public function getIssues()
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $service->
        $jira = $this->container->get('canal_tp_scrum_board_it.jira.provider');
        return $jira->getIssues();
    }
}
