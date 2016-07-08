<?php

namespace ScrumBoardItBundle\Services\Api;

use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Entity\Issue\Task;
use ScrumBoardItBundle\Entity\Issue\SubTask;
use ScrumBoardItBundle\Entity\Issue\IssueInterface;
use ScrumBoardItBundle\Form\Type\Search\GithubSearchType;

/**
 * GitHub service.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ApiGithub extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function getProjects()
    {
        $api = $this->getProjectApi();

        // Multipagination
        $projects = array();
        do {
            $page = $this->apiCaller->call($this->getUser(), $api);
            foreach ($page['content'] as $project) {
                $projects['Propriétaire: '.$project->owner->login][$project->name] = $project->full_name;
                if ($page['links'][0]['rel'] === 'first') {
                    $api = null;
                } else {
                    $match = '';
                    preg_match('/<(.*)>/', $page['links'][0][0], $match);
                    $api = $match[1];
                }
            }
        } while (!empty($api));
        ksort($projects, SORT_NATURAL | SORT_FLAG_CASE);

        return $projects;
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
            foreach ($data['content'] as $sprint) {
                $sprints['Actif'][$sprint->title] = $sprint->number;
            }
        }

        return $sprints;
    }

    /**
     * {@inheritdoc}
     */
    public function searchIssues($searchFilters = null)
    {
        $api = $this->getIssueApi($searchFilters);
        if (!empty($api)) {
            $data = $this->apiCaller->call($this->getUser(), $api);
            $issues = array();
            foreach ($data['content'] as $issue) {
                array_push($issues, $this->getIssue($issue, $searchFilters['project']));
            }

            return $issues;
        }

        return;
    }

    /**
     * Return issue based on API result.
     *
     * @param \stdClass $issue
     * @param string    $project
     *
     * @return IssueInterface
     */
    private function getIssue($issue, $project)
    {
        $task = new Task();
        $labels = $issue->labels;

        foreach ($labels as $label) {
            switch ($label->name) {
                case 'Printed':
                    $task->setPrinted(true);
                    break;
                case 'Subtask':
                    $task->setType('subtask');
                    break;
                case 'POC':
                    $task->setProofOfConcept(true);
                    break;
                case 'User Story':
                    $task->setUserStory(true);
                    break;
                default:
                    break;
            }
        }

        $parameters = array();
        $body = '';
        // Search for parameters in square brackets
        preg_match_all("/\[(.*?)\]/", $issue->body, $parameters);
        $body = preg_replace("/\[(.*?)\]/", '', $issue->body);
        foreach ($parameters[1] as $parameter) {
            $values = explode(':', $parameter);
            switch (trim($values[0])) {
                // Complexity field
                case 'CT':
                    $task->setComplexity(trim($values[1]));
                    break;
                // Time Box field
                case 'TB':
                    $task->setTimeBox(trim($values[1]));
                    break;
                default:
                    break;
            }
        }

        $task->setId($issue->number);
        $task->setNumber($issue->number);
        $task->setProject(explode('/', $project)[1]);
        $task->setTitle($issue->title);
        $task->setDescription($body);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectedIssues(Request $request, $selected)
    {
        $issues = array();
        $filters = $request->getSession()->get('filters');
        if (empty($selected)) {
            $issues = $this->searchIssues($filters);
        } else {
            foreach ($selected as $selectedIssue) {
                $url = $this->getBaseApi($filters['project']).'/issues/'.$selectedIssue;
                $data = $this->apiCaller->call($this->getUser(), $url);
                $issue = $this->getIssue($data['content'], $filters['project']);
                array_push($issues, $issue);
            }
        }

        return $issues;
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
        $searchFilters = $request->get('github_search') ?: array();

        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
        }

        $searchFilters['projects'] = $this->getProjects();
        $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);

        // Initialise sprint even no sprint is selected
        if (empty($searchFilters['sprint'])) {
            $searchFilters['sprint'] = null;
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
                $url = $this->getBaseApi($request->getSession()->get('filters')['project']).
                '/issues/'.$issue.'/labels';
                $content = '["Printed"]';
                $this->apiCaller->send($this->getUser(), $url, $content, 1);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType()
    {
        return GithubSearchType::class;
    }

    /**
     * Return url for searching projects.
     *
     * @return string
     */
    private function getProjectApi()
    {
        $api = 'user/repos';

        return $this->config['host'].$api;
    }

    /**
     * Return url for searching sprints.
     *
     * @param string $project
     *
     * @return string
     */
    private function getSprintApi($project)
    {
        $api = $this->getBaseApi($project);

        return $api.'/milestones';
    }

    /**
     * Return base url for the api.
     *
     * @param string $project
     *
     * @return string
     */
    private function getBaseApi($project)
    {
        $api = 'repos/'.$project;

        return $this->config['host'].$api;
    }

    /**
     * Return url for searching issues.
     *
     * @param array $searchFilters
     *
     * @return string
     */
    private function getIssueApi($searchFilters)
    {
        if (!empty($searchFilters['project'])) {
            $api = $this->getBaseApi($searchFilters['project']).'/issues';
            if (!empty($searchFilters['sprint'])) {
                $api .= '?milestone='.$searchFilters['sprint'];
            }

            return $api;
        }

        return;
    }
}
