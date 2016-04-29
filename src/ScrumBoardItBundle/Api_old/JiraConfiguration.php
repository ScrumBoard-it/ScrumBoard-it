<?php
namespace ScrumBoardItBundle\Api;

/**
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraConfiguration extends ApiCallConfigurationInterface
{

    public function setUri($uri)
    {
        $this->uri = '/rest/api/latest/' . $uri;
        
        return $this;
    }
}
