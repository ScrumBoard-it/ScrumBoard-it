<?php

namespace ScrumBoardItBundle\Entity\Project;

class Project {

    private $id;
    private $value;

    public function __construct($id, $title) {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getValue() {
        return $this->value;
    }
}
