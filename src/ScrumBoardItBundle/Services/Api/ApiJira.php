<?php

namespace ScrumBoardItBundle\Services\Api;

use ScrumBoardItBundle\Entity\Issue\Task;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Search\JiraSearchType;

/**
 * Jira service.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ApiJira extends AbstractApi
{
    const REST_API = 'rest/api/latest/';
    const REST_AGILE = 'rest/agile/latest/';
    const LABEL_US = 'Récit';
    const LABEL_SUBTASK = 'Sous-tâche';
    const LABEL_BOGUE = 'Bogue';
    const LABEL_POC = 'POC';
    const MAX_RESULTS = 50;

    /**
     * {@inheritdoc}
     */
    public function searchIssues($searchFilters = array())
    {
        if (!empty($searchFilters['sprint'])) {
            $api = $this->getIssuesApi('sprint='.$searchFilters['sprint']);
            $data = $this->apiCaller->call($this->getUser(), $api);

            return $this->getIssues($data['content']);
        }

        return array();
    }

    /**
     * Return issues based on API results.
     *
     * @param \stdClass $data
     *
     * @return array
     */
    private function getIssues($data)
    {
        $issues = array();
        foreach ($data->issues as $issue) {
            $task = new Task();

            $task->setId($issue->key);
            $number = str_replace($issue->fields->project->key.'-', '', $issue->key);
            $task->setNumber($number);
            $task->setProject($issue->fields->project->key);
            $task->setTitle($issue->fields->summary);
            $task->setDescription($issue->fields->description);
            $task->setPrinted((!empty($issue->fields->labels[0]) && $issue->fields->labels[0] === $this->config['printed_tag']));
            $task->setUserStory($issue->fields->issuetype->name === self::LABEL_US);
            $task->setProofOfConcept(in_array(self::LABEL_POC, $issue->fields->labels));
            if ($issue->fields->issuetype->name === self::LABEL_SUBTASK) {
                $task->setType('subtask');
            }
            if (property_exists($issue->fields, $this->config['complexity_field'])) {
                $task->setComplexity($issue->fields->{$this->config['complexity_field']});
            }
            $task->setTimeBox(round($issue->fields->aggregatetimeoriginalestimate / 3600, 0).' h');
            $issues[$issue->id] = $task;
        }

        return $issues;
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectedIssues(Request $request, $selected = array())
    {
        $sprint = $request->getSession()->get('filters')['sprint'];
        if (!empty($selected)) {
            $jql = 'issueKey IN ('.implode(',', $selected).')';
        } elseif (!empty($sprint)) {
            $template = 'sprint=%d AND status not in (Closed)';
            $jql = sprintf($template, $sprint);
        }
        if (!empty($jql)) {
            $url = $this->getIssuesApi(urlencode($jql));
            $data = $this->apiCaller->call($this->getUser(), $url);

            return $this->getIssues($data['content']);
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchFilters(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('filters')) {
            $this->initFilters($session);
        }
        $searchFilters = $request->get('jira_search') ?: array();

        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
        }

        $searchFilters['projects'] = $this->getProjects();
        $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);

        if (empty($searchFilters['sprint'])) {
            $searchFilters['sprint'] = isset($searchFilters['sprints']['Actif']) ? array_values($searchFilters['sprints']['Actif'])[0] : null;
        }

        $session->set('filters', array(
            'project' => $searchFilters['project'],
            'sprint' => $searchFilters['sprint'],
        ));

        return $searchFilters;
    }

    /**
     * {@inheritdoc}
     */
    public function addFlag(Request $request, $selected)
    {
        if (!empty($selected)) {
            foreach ($selected as $issue) {
                $api = $this->getFlagIssuesApi().$issue;
                $tag = '"'.$this->config['printed_tag'].'"';
                $content = '{"update":{"labels":[{"add":'.$tag.'}]}}';
                $this->apiCaller->puting($this->getUser(), $api, $content);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSprints($project)
    {
        $sprints = array();
        if ($project !== null) {
            $api = $this->getSprintApi($project);
            $data = $this->apiCaller->call($this->getUser(), $api);
            foreach ($data['content']->values as $sprint) {
                $state = $sprint->state == 'active' ? 'Actif' : 'Futurs';
                $sprints[$state][$sprint->name] = $sprint->id;
            }
            if (!empty($sprints['Futurs'])) {
                asort($sprints['Futurs'], SORT_NATURAL | SORT_FLAG_CASE);
            }
        }

        return $sprints;
    }

    /**
     * Projects getter.
     *
     * @return array
     */
    public function getProjects()
    {
        $projects = array();
        $startAt = 0;
        $api = $this->getProjectApi();
        do {
            $data = $this->apiCaller->call($this->getUser(), $api.'&startAt='.$startAt);
            foreach ($data['content']->values as $project) {
                $projects[$project->name] = $project->id;
            }
            // Multipagination
            $startAt += self::MAX_RESULTS;
        } while (!$data['content']->isLast);
        ksort($projects, SORT_NATURAL | SORT_FLAG_CASE);

        return $projects;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType()
    {
        return JiraSearchType::class;
    }

    /**
     * Sprint API getter.
     *
     * @param int $project
     *
     * @return string
     */
    private function getSprintApi($project)
    {
        $api = self::REST_AGILE.'board/'.$project.'/sprint?state=active&state=future';

        return $this->getUser()->getJiraUrl().$api;
    }

    /**
     * Project API getter.
     *
     * @param int $maxResults
     *
     * @return string
     */
    private function getProjectApi($maxResults = self::MAX_RESULTS)
    {
        $api = self::REST_AGILE.'board?maxResults='.$maxResults;

        return $this->getUser()->getJiraUrl().$api;
    }

    /**
     * Issues API getter.
     *
     * @param string $jql
     *
     * @return string
     */
    private function getIssuesApi($jql = '')
    {
        $api = self::REST_API.'search?jql='.$jql;

        return $this->getUser()->getJiraUrl().$api;
    }

    /**
     * addFlag API getter.
     *
     * @return string
     */
    private function getFlagIssuesApi()
    {
        $api = self::REST_API.'issue/';

        return $this->getUser()->getJiraUrl().$api;
    }
}
