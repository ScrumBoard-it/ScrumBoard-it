<?php

namespace CanalTP\ScrumBoardItBundle\IssuesProvider;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssuesProviderInterface {
    /**
     * Retrieve issues
     * @return \CanalTP\ScrumBoardItBundle\Collection\IssuesCollectionInterface
     */
    public function getIssues();
}
