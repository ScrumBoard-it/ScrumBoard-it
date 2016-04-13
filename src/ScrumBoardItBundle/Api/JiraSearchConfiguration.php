<?php

namespace ScrumBoardItBundle\Api;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraSearchConfiguration extends JiraConfiguration
{
    public function __construct()
    {
        $this->setUri('search');
        $this->setProcessors(array(
            'JsonToObject',
            'JiraSearch',
        ));
    }
}
