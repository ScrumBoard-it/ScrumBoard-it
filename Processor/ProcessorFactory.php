<?php

namespace CanalTP\ScrumBoardItBundle\Processor;

use CanalTP\ScrumBoardItBundle\FactoryInterface;

/**
 * Description of ProcessorFactory
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class ProcessorFactory extends FactoryInterface {
    private $context;
    
    public function __construct($suffix = null) {
        parent::__construct('Processor');
    }
    
    public function get($name) {
        $service = parent::get($name);
        return $service->setContext($this->getContext());
    }
    
    public function getContext() {
        return $this->context;
    }

    public function setContext($context) {
        $this->context = $context;
        return $this;
    }
}
