<?php

namespace CanalTP\ScrumBoardItBundle\Processor;

use CanalTP\ScrumBoardItBundle\Api\ApiCallBuilderInterface;
use CanalTP\ScrumBoardItBundle\Collection\IssuesCollection;
use CanalTP\ScrumBoardItBundle\Entitie\Task;
use CanalTP\ScrumBoardItBundle\Entitie\SubTask;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JiraSearchProcessor extends AbstractProcessor {
    private $collection;
    private $printedTag;
    
    public function __construct() {
        $this->collection = new IssuesCollection();
    }
    
    public function setContext(ApiCallBuilderInterface $context) {
        return parent::setContext($context);
        $options = $context->getOptions();
        $this->printedTag = $options['tag'];
    }

    
    public function handle()
    {
        $data = $this->getContext()->getResult();
        if (isset($data->issues)) {
            $issues = $data->issues;
            $this->normalize($issues);
            $this->getContext()->setResult($this->collection);
        }
    }
    
    private function normalize($issues) {
        foreach ($issues as $issue) {
            if (isset($issue->fields->parent)) {
                $item = $this->hydrateSubTask($issue);
            } else {
                $item = $this->hydrateTask($issue);
            }
            $this->collection->add($item);
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
