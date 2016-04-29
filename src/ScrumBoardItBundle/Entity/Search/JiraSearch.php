<?php
namespace ScrumBoardItBundle\Entity\Search;

/**
 * Jira search
 */
class JiraSearch
{

    /**
     * Projects
     * 
     * @var array $projects
     */
    private $projects = array();

    /**
     * Sprints
     * 
     * @var array $sprints
     */
    private $sprints = array();

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
     * Projects getter
     * 
     * @return array
     */
    function getProjects()
    {
        return $this->projects;
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
     * Project getter
     * 
     * @return int
     */
    function getProject()
    {
        return $this->project;
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
