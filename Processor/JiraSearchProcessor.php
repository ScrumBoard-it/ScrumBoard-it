<?php

namespace CanalTP\ScrumBoardItBundle\Processor;

use CanalTP\ScrumBoardItBundle\Collection\IssuesCollection;
use CanalTP\ScrumBoardItBundle\Entitie\Task;
use CanalTP\ScrumBoardItBundle\Entitie\SubTask;

/**
 * Description of JiraSearchProcessor
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraSearchProcessor implements ApiProcessorInterface {
    private $printedTag;
    private $result;
    
    public function __construct()
    {
        $this->result = new IssuesCollection();
        $this->printedTag = 'ScrumBoardIt';
    }
    
    public function getPrintedTag() {
        return $this->printedTag;
    }

    public function setPrintedTag($printedTag) {
        $this->printedTag = $printedTag;
        return $this;
    }
    
    public function handle($result)
    {
        if (isset($result->issues)) {
            $issues = $result->issues;
            $this->normalize($issues);
        }
        return $this->result;
    }
    
    private function normalize($issues) {
        foreach ($issues as $issue) {
            if (isset($issue->fields->parent)) {
                $item = $this->hydrateSubTask($issue);
            } else {
                $item = $this->hydrateTask($issue);
            }
            $this->result->add($item);
        }
    }
    
    private function hydrateTask($issue) {
        $task = new Task();
        list($project, $id) = explode('-', $issue->key, 2);
        $task->setProject($project);
        $task->setId($id);
        $task->setLink($issue->self);
        $task->setPrinted(in_array($this->printedTag, $issue->fields->labels));
        if (isset($issue->fields->customfield_11108)) {
            $task->setComplexity($issue->fields->customfield_11108);
        }
        $task->setTitle($issue->fields->summary);
        return $task;
    }
    
    private function hydrateSubTask($issue) {
        $task = new SubTask();
        list($project, $id) = explode('-', $issue->key, 2);
        $task->setProject($project);
        $task->setId($id);
        $task->setLink($issue->self);
        $task->setPrinted(in_array($this->printedTag, $issue->fields->labels));
        if (isset($issue->fields->customfield_11108)) {
            $task->setComplexity($issue->fields->customfield_11108);
        }
        $task->setTitle($issue->fields->summary);
        $parts = explode('-', $issue->fields->parent->key, 2);
        $task->setTask($parts[1]);
        return $task;
    }
}
