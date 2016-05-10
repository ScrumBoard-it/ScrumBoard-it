<?php

namespace CanalTP\ScrumBoardItBundle\Service;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ServiceInterface
{
    public function setBoardId($id);
    public function getBoardId();
    public function setSprintId($id);
    public function getSprintId();
    public function setIssueTag($tag);
    public function getIssueTag();
    public function getBoards();
    public function getSprints();
    public function getIssues($selected = array());
    public function addFlag($selected = array());
    public function getOptions();
    public function setOptions(array $options);
}
