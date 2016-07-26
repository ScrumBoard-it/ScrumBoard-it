<?php

namespace ScrumBoardItBundle\Services\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle\Services\ApiCaller;
use ScrumBoardItBundle\Entity\Mapping\Favorites;
use ScrumBoardItBundle\Services\ProfileProvider;
use ScrumBoardItBundle\Entity\Mapping\User;

/**
 * Abstract Api.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
abstract class AbstractApi
{
    /**
     * Api configuration.
     *
     * @var mixed
     */
    protected $config;

    /**
     * @var ApiCaller
     */
    protected $apiCaller;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param TokenStorage  $token
     * @param mixed         $config
     * @param ApiCaller     $apiCaller
     * @param EntityManager $em
     */
    public function __construct(TokenStorage $token, $config, ApiCaller $apiCaller, EntityManager $em)
    {
        $this->user = $token->getToken()->getUser();
        $this->config = $config;
        $this->apiCaller = $apiCaller;
        $this->em = $em;
    }

    /**
     * Initialize filters in session variable.
     *
     * @param Session $session
     */
    protected function initFilters(Session $session)
    {
        $session->set('filters', array(
            'project' => null,
            'sprint' => null,
        ));
    }

    /**
     * Get configuration.
     *
     * @return User
     */
    public function getDatabaseUser()
    {
        $user = $this->em->getRepository('ScrumBoardItBundle:Mapping\User')->find($this->user->getId());

        return $user;
    }

    /**
     * Initialize filters for Api calls.
     *
     * @param array $searchFilters
     *
     * @return array
     */
    protected function initSearchFilters($searchFilters)
    {
        $init = array_fill_keys(array('projects', 'sprints', 'project', 'sprint'), null);
        if (empty($searchFilters)) {
            $searchFilters = array();
        }

        return array_merge($init, $searchFilters);
    }

    /**
     * Return type of the form.
     *
     * @return AbstractType
     */
    abstract public function getFormType();

    /**
     * Return the projects list.
     *
     * @return array
     */
    abstract public function getProjects();

    /**
     * Return the sprints list according to a project.
     *
     * @param string $project
     */
    abstract public function getSprints($project);

    /**
     * Return the issues list.
     *
     * @param array $searchFilters
     *
     * @return \stdClass
     */
    abstract public function searchIssues($searchFilters = null);

    /**
     * Return the selected issues list.
     *
     * @param Request $request
     * @param array   $selected
     */
    abstract public function getSelectedIssues(Request $request, $selected);

    /**
     * Return the search list.
     *
     * @param Request $request
     *
     * @return array
     */
    abstract public function getSearchFilters(Request $request);

    /**
     * Add printed flag on selected issues.
     *
     * @param array $selected
     */
    abstract public function addFlag(Request $request, $selected);

    /**
     * Return the favorites projects of one bugtracker.
     *
     * @param User            $user
     * @param ProfileProvider $profileProvider
     *
     * @return array
     */
    abstract public function getFavorites(Request $request, ProfileProvider $profileProvider);

    /**
     * Add a project to the favorite list.
     *
     * @param Request         $request
     * @param ProfileProvider $profileProvider
     */
    abstract public function addFavorite(Request $request, ProfileProvider $profileProvider);
}
