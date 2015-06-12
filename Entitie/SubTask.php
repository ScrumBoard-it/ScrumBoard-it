<?php

namespace CanalTP\ScrumBoardItBundle\Entitie;

/**
 * Description of SubTask.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class SubTask extends AbstractIssue
{
    private $task;

    public function __construct()
    {
        $this->setType('subtask');
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }
}
