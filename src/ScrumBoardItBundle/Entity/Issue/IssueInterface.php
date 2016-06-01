<?php
namespace ScrumBoardItBundle\Entity\Issue;

/**
 * Issue Interface
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssueInterface
{
    /**
     * Project getter
     *
     * @return string
     */
    public function getProject();

    /**
     * Project setter
     * @param string $project
     * @return string
     */
    public function setProject($project);

    /**
     * ID getter
     *
     * @return number
     */
    public function getId();

    /**
     * ID setter
     * @param number $id
     * @return number
     */
    public function setId($id);

    /**
     * Title getter
     *
     * @return string
     */
    public function getTitle();

    /**
     * Title setter
     * @param string $title
     * @return string
     */
    public function setTitle($title);

    /**
     * Complexity getter
     *
     * @return number
     */
    public function getComplexity();

    /**
     * Complexity setter
     * @param number $complexity
     * @return number
     */
    public function setComplexity($complexity);
}
