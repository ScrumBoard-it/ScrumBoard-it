<?php

namespace CanalTP\ScrumBoardItBundle\Entitie;

/**
 * Description of AbstractIssue
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class AbstractIssue implements IssueInterface
{
    private $type;
    private $link;
    private $printed;
    private $userStory;
    private $proofOfConcept;
    private $project;
    private $id;
    private $title;
    private $complexity;
    private $businessValue;
    private $description;
    private $timeBox;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function isPrinted()
    {
        return $this->printed;
    }

    public function setPrinted($printed)
    {
        $this->printed = $printed;
        return $this;
    }

    public function isUserStory()
    {
        return $this->userStory;
    }

    public function setUserStory($value)
    {
        $this->userStory = $value;
        return $this;
    }

    public function isProofOfConcept()
    {
        return $this->proofOfConcept;
    }

    public function setProofOfConcept($proofOfConcept)
    {
        $this->proofOfConcept = $proofOfConcept;
        return $this;
    }

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

    public function getBusinessValue()
    {
        return $this->businessValue;
    }

    public function setBusinessValue($value)
    {
        $this->businessValue = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getTimeBox()
    {
        return $this->timeBox;
    }

    public function setTimeBox($timeBox)
    {
        $this->timeBox = $timeBox;
        return $this;
    }
}
