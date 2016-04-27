<?php

namespace ScrumBoardItBundle\Jira;

use ScrumBoardItBundle\Jira\AbstractApi;
use ScrumBoardItBundle\Entity\Issue\SubTask;
use ScrumBoardItBundle\Entity\Issue\Task;

class ApiJira extends AbstractApi {

    public function getIssues($id) {
        $data = $this->curl($this->getData()['host'] . $this->getData()['api'] . 'search?jql=sprint=' . $id);
        $issues = array();
        for ($i = 0; $i < $data['total']; $i++) {
            if ($data['issues'][$i]['fields']['issuetype']['subtask'] === true) {
                $issue = new SubTask();
                //Référencer la tâche parente
            } else {
                $issue = new Task();
                $issue->setComplexity($data['issues'][$i]['fields']['customfield_11108']);
            }
            $issue->setId($data['issues'][$i]['id']);
            $issue->setProject($data['issues'][$i]['key']);
            $issue->setTitle($data['issues'][$i]['fields']['summary']);
            if (!empty($data['issues'][$i]['fields']['labels']) &&
                    $data['issues'][$i]['fields']['labels'][0] === "Post-it") {
                $issue->setPrinted(true);
            } else {
                $issue->setPrinted(false);
            }
            array_push($issues, $issue);
        }

        return $issues;
    }

    public function getProjects() {
        $data = $this->curl($this->getData()['host'] . $this->getData()['agile'] . 'board?orderBy=name&maxResults=-1');
        $boards = array();
        for ($i = 0; $i < count($data['values']); $i++) {
            $boards[$data['values'][$i]['id']] = $data['values'][$i]['name'];
        }
        asort($boards, SORT_NATURAL | SORT_FLAG_CASE);

        return $boards;
    }

    public function getSprints($id) {
        $data = $this->curl($this->getData()['host'] . $this->getData()['agile'] . 'board/' . $id .
                '/sprint?state=active&state=future');
        $sprints = array();
        for ($i = 0; $i < count($data['values']); $i++) {
            $sprints[$data['values'][$i]['id']] = $data['values'][$i]['name'];
        }
        
        return $sprints;
    }
}
