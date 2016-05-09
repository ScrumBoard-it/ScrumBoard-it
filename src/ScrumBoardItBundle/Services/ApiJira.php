<?php
namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Entity\Issue\SubTask;
use ScrumBoardItBundle\Entity\Issue\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Jira API
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ApiJira extends AbstractApi
{

    /**
     * Rest API
     *
     * @var string
     */
    const REST_API = 'rest/api/latest/';

    /**
     * Rest AGILE
     *
     * @var string
     */
    const REST_AGILE = 'rest/agile/latest/';

    /**
     *
     * {@inheritdoc}
     *
     */
    public function searchIssues($searchFilters = array())
    {
        if (! empty($searchFilters['sprint'])) {
            $api = $this->getIssuesApi('sprint=' . $searchFilters['sprint']);
            $data = $this->call($api);
            return $this->getIssues($data);
        }
        
        return array();
    }
    
    private function getIssues($data) {

        $issues = array();
        foreach ($data->issues as $issue) {
            if ($issue->fields->issuetype->subtask === true) {
                $task = new SubTask();
                $task->setUserStory(true);
            } else {
                $task = new Task();
                $task->setComplexity($issue->fields->customfield_11108);
                $task->setProofOfConcept(false);
            }
            $task->setId($issue->id);
            $task->setProject($issue->key);
            $task->setTitle($issue->fields->summary);
            $task->setPrinted(! empty($issue->fields->labels));
        
            $issues[$issue->id] = $task;
        }
        
        return $issues;
    }

    public function getSelectedIssues(Request $request, $selected = array())
    {
        $sprint = $request->getSession()->get('filters')['sprint'];
        if (! empty($selected)) {
            $jql = 'issueKey IN (' . implode(',', $selected) . ')';
        } elseif (! empty($sprint)) {
            $template = 'sprint=%d AND status not in (Closed)';
            $jql = sprintf($template, $sprint);
        }
        if (! empty($jql)) {
            $url = $this->getIssuesApi(urlencode($jql));
            $data = $this->call($url);
            return $this->getIssues($data);
        }
        return array();
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getSearchFilters(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('filters'))
            $this->initFilters($session);
        $searchFilters = $request->get('jira_search') ?: array();
        
        $searchFilters['projects'] = $this->getProjects();
        if (empty($searchFilters['project']))
            $searchFilters['project'] = null;
        $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);
        if (empty($searchFilters['sprint']))
            $searchFilters['sprint'] = isset($searchFilters['sprints']['Actif']) ? array_values($searchFilters['sprints']['Actif'])[0] : null;
        $this->sprint = $searchFilters['sprint'];
        
        $session->set('filters', array(
            'project' => $searchFilters['project'],
            'sprint' => $searchFilters['sprint']
        ));
        
        return $searchFilters;
    }

    public function initFilters(Session $session)
    {
        $session->set('filters', array(
            'project' => null,
            'sprint' => null
        ));
    }

    /**
     * Sprints getter according to a project
     *
     * @param int $project            
     * @return array
     */
    public function getSprints($project)
    {
        $sprints = array();
        if ($project !== null) {
            $api = $this->getSprintApi($project);
            $data = $this->call($api);
            foreach ($data->values as $sprint) {
                $state = $sprint->state == 'active' ? 'Actif' : 'Futurs';
                $sprints[$state][$sprint->name] = $sprint->id;
            }
            if (! empty($sprints['Futurs'])) {
                asort($sprints['Futurs'], SORT_NATURAL | SORT_FLAG_CASE);
            }
        }
        
        return $sprints;
    }

    /**
     * Projects getter
     *
     * @return array
     */
    public function getProjects()
    {
        $api = $this->getProjectApi();
        $data = $this->call($api);
        $projects = array();
        foreach ($data->values as $sprint) {
            $projects[$sprint->name] = $sprint->id;
        }
        ksort($projects, SORT_NATURAL | SORT_FLAG_CASE);
        
        return $projects;
    }

    /**
     * Sprint API getter
     *
     * @param int $project            
     */
    private function getSprintApi($project)
    {
        $api = self::REST_AGILE . 'board/' . $project . '/sprint?state=active&state=future';
        
        return $this->config['host'] . $api;
    }

    /**
     * Project API getter
     */
    private function getProjectApi($maxResults = '-1')
    {
        $api = self::REST_AGILE . 'board?maxResults=' . $maxResults;
        
        return $this->config['host'] . $api;
    }

    /**
     * Issues API getter
     *
     * @param string $jql            
     */
    private function getIssuesApi($jql = '')
    {
        $api = self::REST_API . 'search?jql=' . $jql;
        
        return $this->config['host'] . $api;
    }
}
