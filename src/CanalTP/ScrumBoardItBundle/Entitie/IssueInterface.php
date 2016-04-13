<?php

namespace CanalTP\ScrumBoardItBundle\Entitie;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssueInterface
{
    public function getProject();
    public function setProject($project);

    public function getId();
    public function setId($id);

    public function getTitle();
    public function setTitle($title);

    public function getComplexity();
    public function setComplexity($complexity);
}
