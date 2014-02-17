<?php

namespace CanalTP\ScrumBoardItBundle\Service;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ServiceInterface {
    public function getIssues();
    public function callApi($api, $params = array());
    public function getOptions();
    public function setOptions(array $options);
}
