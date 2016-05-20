<?php
namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Services\AbstractApi;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Entity\Issue\Task;

class ApiGitHub extends AbstractApi
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getProjects()
    {
        $api = $this->getProjectApi();
        $data = $this->call($api);
        $projects = array();
        foreach ($data as $project) {
            $projects[$project->name] = $project->name;
        }
        ksort($projects, SORT_NATURAL | SORT_FLAG_CASE);
        
        return $projects;
    }

    public function getSprints($project)
    {
        $api = $this->getSprintApi($project);
        $data = $this->call($api);
        $sprints = array();
        foreach ($data as $sprint)
            $sprints['Actif'][$sprint->title] = $sprint->number;
        
        return $sprints;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function searchIssues($searchFilters = null)
    {
        $api = $this->getIssueApi($searchFilters);
        if (! empty($api)) {
            $data = $this->call($api);
            
            return $this->getIssues($data, $searchFilters);
        }
        return;
    }

    private function getIssues($data, $searchFilters)
    {
        $issues = array();
        foreach ($data as $issue) {
            $task = new Task();
            $task->setUserStory(true);
            $task->setId($issue->number);
            $task->setNumber($issue->number);
            $task->setProject($searchFilters['project']);
            $task->setTitle($issue->title);
            
            $issues[$issue->number] = $task;
        }
        
        return $issues;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getSelectedIssues(Request $request, $selected)
    {
        $issues = array();
        $filters = $request->getSession()->get('filters');
        if (empty($selected))
            $issues = $this->searchIssues($filters);
        else {
            foreach ($selected as $selectedIssue) {
                $url = $this->getOriginApi($filters['project']) . '/issues/' . $selectedIssue;
                $data = $this->call($url);
                $issue = $this->getIssues(array(
                    0 => $data
                ), $filters);
                $issues = array_merge($issues, $issue);
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
        $session = $request->getSession();
        if ($session->has('filters'))
            $this->initFilters($session);
        $searchFilters = $request->get('github_search') ?: array();
        
        // Che
        $searchFilters['projects'] = $this->getProjects();
        
        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
            $searchFilters['sprints'] = null;
        } else
            $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);
            
            // Initialise sprint even no sprint is selected
        if (empty($searchFilters['sprint']))
            $searchFilters['sprint'] = null;
        
        $session->set('filters', array(
            'project' => $searchFilters['project'],
            'sprint' => $searchFilters['sprint']
        ));
        
        return $searchFilters;
    }

    public function addFlag($selected)
    {}

    private function getProjectApi()
    {
        $api = 'users/' . $this->getUser()->getUsername() . '/repos';
        
        return $this->config['host'] . $api;
    }

    private function getSprintApi($project)
    {
        $api = $this->getOriginApi($project);
        
        return $api . '/milestones';
    }

    private function getOriginApi($project)
    {
        $api = 'repos/' . $this->getUser()->getUsername() . '/' . $project;
        
        return $this->config['host'] . $api;
    }

    private function getIssueApi($searchFilters)
    {
        if (! empty($searchFilters['project'])) {
            $api = $this->getOriginApi($searchFilters['project']) . '/issues';
            $api .= empty($searchFilters['sprint']) ? '' : ('?milestone=' . $searchFilters['sprint']);
            
            return $api;
        }
        
        return;
    }
}