<?php
namespace ScrumBoardItBundle\Services;

use ScrumBoardItBundle\Services\AbstractApi;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Entity\Issue\Task;

class ApiGitHub extends AbstractApi
{

    const API_USERS = 'users/';

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
            $task->setProject($searchFilters['project']);
            $task->setTitle($issue->title);
            
            $issues[$issue->id] = $task;
        }
        
        return $issues;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getSelectedIssues(Request $request, $selected)
    {}

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
        $searchFilters = $request->get('git_hub_search') ?: array();
        
        $searchFilters['projects'] = $this->getProjects();
        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
            $searchFilters['sprints'] = null;
        } else
            $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);
        if (empty($searchFilters['sprint']))
            $searchFilters['sprint'] = null;
        
        $session->set('filters', array(
            'project' => $searchFilters['project'],
            'sprint' => $searchFilters['sprint']
        ));
        
        return $searchFilters;
    }

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
        $content = $this->call($this->config['host'] . $api);
        if ($content->fork) {
            $user = $content->parent->owner->login;
            $realProject = $content->parent->name;
            $api = 'repos/' . $user . '/' . $realProject;
        }
        
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