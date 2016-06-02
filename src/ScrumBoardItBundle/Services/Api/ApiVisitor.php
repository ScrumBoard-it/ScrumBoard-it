<?php
namespace ScrumBoardItBundle\Services\Api;

use ScrumBoardItBundle\Services\Api\AbstractApi;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Search\VisitorSearchType;

class ApiVisitor extends AbstractApi
{
    public function getFormType()
    {
        return VisitorSearchType::class;
    }
    
    public function getProjects()
    {
        
    }
    
    public function getSprints($project)
    {
        
    }
    
    public function searchIssues($searchFilters = null)
    {
        
    }
    
    public function getSelectedIssues(Request $request, $selected)
    {
        
    }
    
    public function getSearchFilters(Request $request)
    {
        
    }
    
    public function addFlag(Request $request, $selected)
    {
        
    }
}