<?php

namespace ScrumBoardItBundle\Entity\Issue;

/**
 * Issue Interface.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssueInterface
{
    /**
     * Project getter.
     *
     * @return string
     */
    public function getProject();

    /**
     * Project setter.
     *
     * @param string $project
     *
     * @return self
     */
    public function setProject($project);

    /**
     * ID getter.
     *
     * @return int
     */
    public function getId();

    /**
     * ID setter.
     *
     * @param int $id
     *
     * @return self
     */
    public function setId($id);

    /**
     * Title getter.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Title setter.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Complexity getter.
     *
     * @return int
     */
    public function getComplexity();

    /**
     * Complexity setter.
     *
     * @param int $complexity
     *
     * @return self
     */
    public function setComplexity($complexity);
}
