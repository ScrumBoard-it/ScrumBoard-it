<?php

namespace CanalTP\ScrumBoardItBundle\Api;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
abstract class ApiCallConfigurationInterface {
    protected $uri;
    protected $parameters;
    protected $processors;
    
    public function __construct() {
        $this->setProcessors(array('JsonToObject'));
    }
    
    public function getUri() {
        return $this->uri;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function setUri($uri) {
        $this->uri = $uri;
        return $this;
    }

    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }
    
    public function getProcessors() {
        return $this->processors;
    }

    public function setProcessors(array $processors) {
        $this->processors = $processors;
        return $this;
    }
}
