<?php
namespace ScrumBoardItBundle\Services\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\AbstractType;

/**
 * Abstract Api
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>        
 */
abstract class AbstractApi
{
    /**
     * Config
     * @var array
     */
    protected $config;

    /**
     * Api Caller
     * @var ApiCaller
     */
    protected $apiCaller;

    /**
     * User
     * @var User
     */
    private $user;

    /**
     * Constructor
     *
     * @param TokenStorage $token            
     * @param array
     */
    public function __construct(TokenStorage $token, $config, $apiCaller)
    {
        $this->user = $token->getToken()->getUser();
        $this->config = $config;
        $this->apiCaller = $apiCaller;
    }

    /**
     * User getter
     *
     * @return User
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * Initialize filters in session variable
     * @param Session $session            
     */
    protected function initFilters(Session $session)
    {
        $session->set('filters', array(
            'project' => null,
            'sprint' => null
        ));
    }

    /**
     * Return type of the form
     *
     * @return AbstractType
     */
    public abstract function getFormType();

    /**
     * Return the projects list
     *
     * @return array
     */
    public abstract function getProjects();

    /**
     * Return the sprints list according to a project
     * @param string $project            
     */
    public abstract function getSprints($project);

    /**
     * Return the issues list
     * @param array $searchFilters            
     * @return \stdClass
     */
    public abstract function searchIssues($searchFilters = null);

    /**
     * Return the selected issues list
     * @param Request $request            
     * @param array $selected            
     */
    public abstract function getSelectedIssues(Request $request, $selected);

    /**
     * Return the searc list
     * @param Request $request            
     * @return array
     */
    public abstract function getSearchFilters(Request $request);

    /**
     * Add printed flag on selected issues
     * @param array $selected            
     */
    public abstract function addFlag(Request $request, $selected);
}