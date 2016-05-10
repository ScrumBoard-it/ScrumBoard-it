<?php
namespace ScrumBoardItBundle\Entity\Search;

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
     * @var array $sprints
     */
    private $sprints = array();

    public function __construct($searchFilters) {
        parent::__construct($searchFilters);
        $this->sprint = $searchFilters['sprint'];
        $this->sprints = $searchFilters['sprints'];
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
