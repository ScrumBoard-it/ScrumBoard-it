<?php

namespace CanalTP\ScrumBoardItBundle\Service;

use CanalTP\ScrumBoardItBundle\Api\JiraSearchConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraIssueConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraBoardConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraCallBuilder;
use CanalTP\ScrumBoardItBundle\Api\ApiCallConfigurationInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * JIRA service
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraService extends AbstractService
{
    /**
     * Board ID
     * @var int $boardId
     */
    private $boardId;

    /**
     * Sprint ID
     * @var int $sprintId
     */
    private $sprintId;

    /**
     * Issue tag
     * @var string $issueTag
     */
    private $issueTag;

    /**
     * Security context
     * @var SecurityContextInterface $securityContext
     */
    private $securityContext;

    /**
     * Constructor
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        $token = $this->securityContext->getToken();
        $options['login'] = $token->getJiraUsername();
        $options['password'] = $token->getJiraPassword();
        parent::setOptions($options);
        $this->setIssueTag($options['tag']);
    }

    /**
     * {@inheritDoc}
     */
    public function setBoardId($boardId)
    {
        $this->boardId = $boardId;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBoardId()
    {
        return $this->boardId;
    }

    /**
     * {@inheritDoc}
     */
    public function setSprintId($sprintId)
    {
        $this->sprintId = $sprintId;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSprintId()
    {
        return $this->sprintId;
    }

    /**
     * {@inheritDoc}
     */
    public function setIssueTag($issueTag)
    {
        $this->issueTag = $issueTag;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getIssueTag()
    {
        return $this->issueTag;
    }

    /**
     * {@inheritDoc}
     */
    public function getBoards()
    {
        $config = new JiraBoardConfiguration();
        $results = $this->getResults($config);
        $values = array();
        if (isset($results->values)) {
            foreach ($results->values as $value) {
                $values[$value->id] = $value->name;
            }
            asort($values, SORT_NATURAL | SORT_FLAG_CASE);
        }
        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function getSprints()
    {
        $config = new JiraBoardConfiguration();
        $values = array();
        if (!empty($this->boardId)) {
            $config->setBoardId($this->boardId);
            $config->setParameters(array(
                'state' => array(
                    'active',
                    'future'
                )
            ));
            $results = $this->getResults($config);
            if (isset($results->values)) {
                foreach ($results->values as $key => $value) {
                    if (!$key && empty($this->sprintId)) {
                        $this->sprintId = $value->id;
                    }
                    $state = $value->state == 'active' ? 'Actif' : 'Futurs';
                    $values[$state][$value->id] = $value->name;
                }
                if (!empty($values['Futurs'])) {
                    asort($values['Futurs'], SORT_NATURAL | SORT_FLAG_CASE);
                }
            }
        }
        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function getIssues($selected = array())
    {
        $config = new JiraSearchConfiguration();
        if (!empty($selected)) {
            $jql = 'issueKey in ('.implode(',', $selected).')';
        } elseif (!empty($this->sprintId)) {
            $template = 'Sprint = %d AND status not in (Closed)';
            $jql = sprintf($template, $this->sprintId);
        }
        if (!empty($jql)) {
            $config->setParameters(array(
                'jql' => $jql
            ));
            return $this->getResults($config);
        }
        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function addFlag($selected = array())
    {
        if (!empty($selected)) {
            $config = new JiraIssueConfiguration();
            foreach ($selected as $issueId) {
                $config->setIssueId($issueId);
                $config->setParameters(
                    '{"update":{"labels":[{"add":"'.$this->issueTag.'"}]}}'
                );
                $this->callApi($config);
            }
        }
    }

    /**
     * Results from API getter
     * @param ApiCallConfigurationInterface $config
     * @return array
     */
    private function getResults(ApiCallConfigurationInterface $config)
    {
        $config->setParameters(array_merge(
            $config->getParameters() ?: array(),
            array(
                'maxResults' => -1
            )
        ));
        return $this->callApi($config);
    }

    /**
     * Api caller
     * @param ApiCallConfigurationInterface $config
     * @return mixed
     */
    private function callApi(ApiCallConfigurationInterface $config)
    {
        $api = new JiraCallBuilder($this->getOptions());
        $api->setApiConfiguration($config);

        return $api->call();
    }
}
