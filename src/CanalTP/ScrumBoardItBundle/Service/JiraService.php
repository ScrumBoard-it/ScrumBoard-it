<?php

namespace CanalTP\ScrumBoardItBundle\Service;

use CanalTP\ScrumBoardItBundle\Api\JiraSearchConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraIssueConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraCallBuilder;
use Symfony\Component\DependencyInjection\Container;

/**
 * Description of JiraService.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraService extends AbstractService
{
    private $sprintId;
    private $issueTag;
    private $container;

    public function setOptions(array $options)
    {
        $user = $this->getContainer()->get('security.token_storage')->getToken()->getUser();
        $password = $this->getContainer()->get('security.token_storage')->getToken()->getJiraPassword();
        $options['login'] = $user;
        $options['password'] = $password;
        parent::setOptions($options);
        $this->setSprintId($options['sprint_id']);
        $this->setIssueTag($options['tag']);
    }

    public function getIssues($selected = array())
    {
        if (empty($selected)) {
            $template = 'Sprint = %d AND status not in (Closed)';
            $jql = sprintf($template, $this->getSprintId());
        } else {
            $jql = 'issueKey in ('.implode(',', $selected).')';
        }

        $config = new JiraSearchConfiguration();
        $config->setParameters(array(
            'jql' => $jql,
            'maxResults' => -1,
        ));
        $api = new JiraCallBuilder($this->getOptions());
        $api->setApiConfiguration($config);

        return $api->call();
    }

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function addFlag($selected = array())
    {
        if (!empty($selected)) {
            $config = new JiraIssueConfiguration();
            foreach ($selected as $issueId) {
                $config->setIssueId($issueId);
                $config->setParameters(
                    '{"update":{"labels":[{"add":"'.$this->getIssueTag().'"}]}}'
                );
                $api = new JiraCallBuilder($this->getOptions());
                $api->setApiConfiguration($config);
                $api->call();
            }
        }
    }

    public function getSprintId()
    {
        return $this->sprintId;
    }

    public function setSprintId($sprintId)
    {
        $this->sprintId = $sprintId;

        return $this;
    }

    public function getIssueTag()
    {
        return $this->issueTag;
    }

    public function setIssueTag($issueTag)
    {
        $this->issueTag = $issueTag;

        return $this;
    }
}
