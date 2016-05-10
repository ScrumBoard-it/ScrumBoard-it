<?php
namespace ScrumBoardItBundle\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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
     * @return array
     */
    protected function call($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $this->getUser()->getHash()
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($content);
    }

    /**
     * Return the projects list
     *
     * @return array
     */
    public abstract function getProjects();

    /**
     * Return the issues list
     *
     * @param array $searchFilters            
     * @return array
     */
    public abstract function getIssues($searchFilters = null);

    /**
     * Return the searc list
     *
     * @param Request $request            
     * @return array
     */
    public abstract function getSearchFilters(Request $request);
}