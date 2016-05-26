<?php
namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Entity\Issue\SubTask;
use ScrumBoardItBundle\Entity\Issue\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use ScrumBoardItBundle\Form\Type\Search\JiraSearchType;

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
            $data = $this->apiCaller->call($this->getUser(), $api);
            return $this->getIssues($data['content']);
        }
        
        return array();
    }

    /**
     * Return issues based on API results
     *
     * @param \stdClass $data            
     * @return array
     */
    private function getIssues($data)
    {
        $issues = array();
        foreach ($data->issues as $issue) {
            if ($issue->fields->issuetype->subtask) {
                $task = new SubTask();
            } else {
                $task = new Task();
                if (! empty($issue->fields->{$this->config['complexity_field']})) {
                    $task->setComplexity($issue->fields->{$this->config['complexity_field']});
                    $task->setUserStory(true);
                } else
                    $task->setProofOfConcept(true);
            }
            $task->setId($issue->key);
            $number = str_replace($issue->fields->project->key . '-', '', $issue->key);
            $task->setNumber($number);
            $task->setProject($issue->fields->project->key);
            $task->setTitle($issue->fields->summary);
            $task->setPrinted((! empty($issue->fields->labels[0]) && $issue->fields->labels[0] === 'Post-it'));
            
            $issues[$issue->id] = $task;
        }
        
        return $issues;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
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
            $data = $this->apiCaller->call($this->getUser(), $url);
            return $this->getIssues($data['content']);
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
            'sprint' => $searchFilters['sprint']
        ));
        
        return $searchFilters;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function addFlag(Request $request, $selected)
    {}

    /**
     *
     * {@inheritdoc}
     *
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
        $data = $this->apiCaller->call($this->getUser(), $api);
        $projects = array();
        foreach ($data['content']->values as $project) {
            $projects[$project->name] = $project->id;
        }
        ksort($projects, SORT_NATURAL | SORT_FLAG_CASE);
        
        return $projects;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getFormType()
    {
        return JiraSearchType::class;
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
     *
     * @param int $maxResults            
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
