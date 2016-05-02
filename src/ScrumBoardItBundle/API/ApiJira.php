<?php
namespace ScrumBoardItBundle\API;

use ScrumBoardItBundle\API\AbstractApi;
use ScrumBoardItBundle\Entity\Issue\SubTask;
use ScrumBoardItBundle\Entity\Issue\Task;
use Symfony\Component\HttpFoundation\Request;

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
    public function getIssues($searchFilters = array())
    {
        $issues = array();
        if (! empty($searchFilters['sprint'])) {
            $api = $this->getIssuesApi('sprint=' . $searchFilters['sprint']);
            $data = $this->call($api);
            
            foreach ($data->issues as $issue) {
                if ($issue->fields->issuetype->subtask === true)
                    $task = new SubTask();
                else {
                    $task = new Task();
                    $task->setComplexity($issue->fields->customfield_11108);
                }
                $task->setId($issue->id);
                $task->setProject($issue->key);
                $task->setTitle($issue->fields->summary);
                $task->setPrinted(! empty($issue->fields->labels));
                
                $issues[$issue->id] = $task;
            }
        }
        
        return $issues;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getSearchFilters(Request $request)
    {
        $searchFilters = $request->get('search') ?: array();
        
        $searchFilters['projects'] = $this->getProjects();
        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
        }
        $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);
        $searchFilters['sprint'] = isset(array_keys($searchFilters['sprints'])[0]) ? array_keys($searchFilters['sprints'])[0] : null;
        
        return $searchFilters;
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
                $sprints[$sprint->id] = $sprint->name;
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
