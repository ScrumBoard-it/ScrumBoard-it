<?php

namespace CanalTP\ScrumBoardItBundle\Service;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ServiceInterface {
    public function getIssues($selected = array());
    public function addFlag($selected = array());
    public function getOptions();
    public function setOptions(array $options);
}
