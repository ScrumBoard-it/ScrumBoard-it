<?php

namespace CanalTP\ScrumBoardItBundle\Entitie;

/**
 * Description of Task.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class Task extends AbstractIssue
{
    public function __construct()
    {
        $this->setType('task');
    }
}
