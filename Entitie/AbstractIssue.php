<?php

namespace CanalTP\ScrumBoardItBundle\Entitie;

/**
 * Description of AbstractIssue
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class AbstractIssue implements IssueInterface {
    private $project;
    private $id;
    private $title;
    private $complexity;
    
    public function getProject() {
        return $this->project;
    }

    public function setProject($project) {
        $this->project = $project;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getComplexity() {
        return $this->complexity;
    }

    public function setComplexity($complexity) {
        $this->complexity = $complexity;
        return $this;
    }
}
