<?php
namespace ScrumBoardItBundle\Entity\Search;

class SearchEntity
{

    /**
     * Projects
     *
     * @var array $projects
     */
    private $projects = array();

    /**
     * Project ID
     *
     * @var int $project
     */
    private $project;

    /**
     * Sprint ID
     *
     * @var int $sprint
     */
    private $sprint;

    /**
     * Sprints
     *
     * @var array $sprints
     */
    private $sprints = array();

    /**
     * Projects getter
     *
     * @return array
     */
    function getProjects()
    {
        return $this->projects;
    }

    public function __construct($searchFilters)
    {
        $this->project = $searchFilters['project'];
        $this->projects = $searchFilters['projects'];
        $this->sprint = $searchFilters['sprint'];
        $this->sprints = $searchFilters['sprints'];
    }

    /**
     * Projects setter
     *
     * @param array $projects            
     * @return self
     */
    function setProjects($projects)
    {
        $this->projects = $projects;
        
        return $this;
    }

    /**
     * Project getter
     *
     * @return int
     */
    function getProject()
    {
        return $this->project;
    }

    /**
     * Project setter
     *
     * @param int $project            
     * @return self
     */
    function setProject($project)
    {
        $this->project = $project;
        
        return $this;
    }

    /**
     * Sprints getter
     *
     * @return array
     */
    function getSprints()
    {
        return $this->sprints;
    }

    /**
     * Sprints setter
     *
     * @param array $sprints            
     * @return self
     */
    function setSprints($sprints)
    {
        $this->sprints = $sprints;
        
        return $this;
    }

    /**
     * Sprint getter
     *
     * @return int
     */
    function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Sprint setter
     *
     * @param int $sprint            
     * @return self
     */
    function setSprint($sprint)
    {
        $this->sprint = $sprint;
        
        return $this;
    }
}