<?php

namespace ScrumBoardItBundle\Entity\Issue;

/**
 * Description of Task.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class Task extends AbstractIssue
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->setType('task');
    }
}
