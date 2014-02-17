<?php

namespace CanalTP\ScrumBoardItBundle\Service;

use CanalTP\ScrumBoardItBundle\Api\JiraSearchConfiguration;
use CanalTP\ScrumBoardItBundle\Api\JiraCallBuilder;

/**
 * Description of JiraService
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraService extends AbstractService {
    private $sprintId;
    
    public function setOptions(array $options) {
        parent::setOptions($options);
        $this->setSprintId($options['sprint_id']);
    }
    
    public function getIssues() {
        $search = 'Sprint = %d AND status not in (Closed)';
        $config = new JiraSearchConfiguration();
        $config->setParameters(array(
            'jql' => sprintf($search, $this->getSprintId()),
            'maxResults' => -1,
        ));
        $api = new JiraCallBuilder($this->getOptions());
        $api->setApiConfiguration($config);
        return $api->call();
    }
    
    public function callApi($api, $params = array()) {
        
    }
    
    public function getSprintId() {
        return $this->sprintId;
    }

    public function setSprintId($sprintId) {
        $this->sprintId = $sprintId;
        return $this;
    }
}
