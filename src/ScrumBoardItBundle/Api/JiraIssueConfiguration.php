<?php

namespace ScrumBoardItBundle\Api;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraIssueConfiguration extends JiraConfiguration
{
    private $baseUri;

    public function __construct()
    {
        parent::__construct();
        $this->setUri('issue');
        $this->baseUri = $this->getUri().'/';
        $this->setMethod('PUT');
    }

    public function setIssueId($issueId)
    {
        $this->uri = $this->baseUri.$issueId;

        return $this;
    }
}
