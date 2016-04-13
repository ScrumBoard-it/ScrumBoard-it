<?php

namespace ScrumBoardItBundle\Api;

/**
 * Jira issuer configuration
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraIssueConfiguration extends JiraConfiguration
{
    /**
     * Base URI
     * @var string $baseUri
     */
    private $baseUri;

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->setUri('issue');
        $this->baseUri = $this->getUri();
        $this->setMethod('PUT');
    }

    /**
     * Issue ID setter
     * @param int $issueId
     * @return JiraIssueConfiguration
     */
    public function setIssueId($issueId)
    {
        $this->uri = $this->baseUri.'/'.$issueId;

        return $this;
    }
}
