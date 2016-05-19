<?php
namespace ScrumBoardItBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 *        
 */
abstract class AbstractApi
{

    protected $config;

    private $user;

    /**
     * Constructor
     *
     * @param TokenStorage $token            
     * @param
     *            array
     */
    public function __construct(TokenStorage $token, $config)
    {
        $this->user = $token->getToken()->getUser();
        $this->config = $config;
    }

    /**
     *
     * @return User
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * Return a array of the API response
     *
     * @param string $url            
     * @return \stdClass
     */
    protected function call($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $this->getUser()->getHash() .
            "\naccept: application/json
            \naccept-language: en-US,en;q=0.8
            \nuser-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/49.0.2623.108 Chrome/49.0.2623.108 Safari/537.36"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) {
            throw new Exception($httpCode, json_decode($content));
        }
        
        return json_decode($content);
    }

    protected function initFilters(Session $session)
    {
        $session->set('filters', array(
            'project' => null,
            'sprint' => null
        ));
    }

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
     *
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
     *
     * @param Request $request            
     * @return array
     */
    public abstract function getSearchFilters(Request $request);
    
    public abstract function addFlag($selected);
}