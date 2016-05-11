<?php
namespace ScrumBoardItBundle\Entity\Search;

abstract class AbstractSearch {
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
     * Projects getter
     *
     * @return array
     */
    function getProjects()
    {
        return $this->projects;
    }


    public function __construct($searchFilters) {
        $this->project = $searchFilters['project'];
        $this->projects = $searchFilters['projects'];
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
}