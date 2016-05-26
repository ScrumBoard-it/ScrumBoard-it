<?php
namespace ScrumBoardItBundle\Entity\Issue;

/**
 * Description of SubTask
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class SubTask extends AbstractIssue
{

    /**
     * Task
     *
     * @var Task $task
     */
    private $task;

    /**
     *
     * {@inheritdoc}
     *
     */
    public function __construct()
    {
        $this->setType('subtask');
    }

    /**
     * Task getter
     *
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Task setter
     *
     * @return self
     */
    public function setTask($task)
    {
        $this->task = $task;
        
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), array(
            'task' => $this->getTask()
        ));
    }
}
