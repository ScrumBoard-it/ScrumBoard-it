<?php

namespace CanalTP\ScrumBoardItBundle\Api;

/**
 * Jira configuration
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraConfiguration extends ApiCallConfigurationInterface
{
    /**
     * Api URI
     * @var string $api
     */
    protected $api = '/rest/api/latest';

    /**
     * URI setter
     * @param string $uri
     * @return JiraConfiguration
     */
    public function setUri($uri)
    {
        $this->uri = $this->api.'/'.$uri;

        return $this;
    }
}
