<?php
namespace ScrumBoardItBundle\Entity\Search;

/**
 * Description of projects/sprints search
 * 
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SearchEntity
{
    /**
     * Projects
     *
     * @var array
     */
    private $projects = array();

    /**
     * Project ID
     *
     * @var number
     */
    private $project;

    /**
     * Sprint ID
     *
     * @var number
     */
    private $sprint;

    /**
     * Sprints
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
     * Projects getter
     *
     * @return array
     */
    function getProjects()
    {
        return $this->projects;
    }

    /**
     * Projects setter
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
     * @param int $sprint            
     * @return self
     */
    function setSprint($sprint)
    {
        $this->sprint = $sprint;
        
        return $this;
    }
}