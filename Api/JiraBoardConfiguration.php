<?php

namespace CanalTP\ScrumBoardItBundle\Api;

/**
 * Jira board configuration
 * @author Vincent Catillon <contact@vincent-catillon.fr>
 */
class JiraBoardConfiguration extends JiraConfiguration
{
    /**
     * Api URI
     * @var string $api
     */
    protected $api = '/rest/agile/1.0';

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
        $this->setUri('board');
        $this->baseUri = $this->getUri();
    }

    /**
     * Board ID setter
     * @param int $boardId
     * @return JiraBoardConfiguration
     */
    public function setBoardId($boardId)
    {
        $this->uri = $this->baseUri.'/'.$boardId.'/sprint';

        return $this;
    }
}
