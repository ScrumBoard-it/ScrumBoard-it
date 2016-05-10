<?php
namespace ScrumBoardItBundle\Entity\Search;

<<<<<<< HEAD
use ScrumBoardItBundle\Entity\Search\AbstractSearch;
/**
 * Jira search
 */
class JiraSearch extends AbstractSearch
{
    /**
     * Sprint ID
     * 
     * @var int $sprint
     */
    private $sprint;
    
    /**
     * Sprints
=======
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
>>>>>>> brieuc/post-it-manager
     * @var array $sprints
     */
    private $sprints = array();

<<<<<<< HEAD
    public function __construct($searchFilters) {
        parent::__construct($searchFilters);
        $this->sprint = $searchFilters['sprint'];
        $this->sprints = $searchFilters['sprints'];
    }
    
=======
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

>>>>>>> brieuc/post-it-manager
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
<<<<<<< HEAD
=======
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
>>>>>>> brieuc/post-it-manager
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
<<<<<<< HEAD
=======
     * Project getter
     * 
     * @return int
     */
    function getProject()
    {
        return $this->project;
    }

    /**
>>>>>>> brieuc/post-it-manager
     * Sprint getter
     * 
     * @return int
     */
    function getSprint()
    {
        return $this->sprint;
    }

    /**
<<<<<<< HEAD
=======
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
>>>>>>> brieuc/post-it-manager
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
