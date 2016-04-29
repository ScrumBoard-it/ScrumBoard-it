<?php
namespace ScrumBoardItBundle\Api;

/**
<<<<<<< 57ff1ade4731b2e75abd5138a313ab5bf3d8ed13:src/ScrumBoardItBundle/Api/JiraIssueConfiguration.php
 * Jira issuer configuration
=======
 *
>>>>>>> Refactoring:src/ScrumBoardItBundle/Api_old/JiraIssueConfiguration.php
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

        $this->baseUri = $this->getUri() . '/';
        $this->setMethod('PUT');
    }

    /**
     * Issue ID setter
     * @param int $issueId
     * @return JiraIssueConfiguration
     */
    public function setIssueId($issueId)
    {
        $this->uri = $this->baseUri . $issueId;
        
        return $this;
    }
}
