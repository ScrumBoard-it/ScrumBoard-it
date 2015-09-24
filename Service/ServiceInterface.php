<?php

namespace CanalTP\ScrumBoardItBundle\Service;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ServiceInterface
{
    public function getBoards();
    public function setBoardId($id);
    public function getSprints();
    public function setSprintId($id);
    public function getIssues($selected = array());
    public function setIssueTag($tag);
    public function addFlag($selected = array());
    public function getOptions();
    public function setOptions(array $options);
}
