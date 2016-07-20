<?php

namespace ScrumBoardItBundle\Entity\Search;

/**
 * Description of projects/sprints search.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SearchEntity
{
    /**
     * Projects.
     *
     * @var array
     */
    private $projects = array();

    /**
     * Project ID.
     *
     * @var int
     */
    private $project;

    /**
     * Sprint ID.
     *
     * @var int
     */
    private $sprint;

    /**
     * Sprints.
     *
     * @var array
     */
    private $sprints = array();

    public function __construct($searchFilters)
    {
        $this->project = $searchFilters['project'];
        $this->projects = $searchFilters['projects'];
        $this->sprint = $searchFilters['sprint'];
        $this->sprints = $searchFilters['sprints'];
    }

    /**
     * Projects getter.
     *
     * @return array
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Projects setter.
     *
     * @param array $projects
     *
     * @return self
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Project getter.
     *
     * @return int
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Project setter.
     *
     * @param int $project
     *
     * @return self
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Sprints getter.
     *
     * @return array
     */
    public function getSprints()
    {
        return $this->sprints;
    }

    /**
     * Sprints setter.
     *
     * @param array $sprints
     *
     * @return self
     */
    public function setSprints($sprints)
    {
        $this->sprints = $sprints;

        return $this;
    }

    /**
     * Sprint getter.
     *
     * @return int
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Sprint setter.
     *
     * @param int $sprint
     *
     * @return self
     */
    public function setSprint($sprint)
    {
        $this->sprint = $sprint;

        return $this;
    }
}
