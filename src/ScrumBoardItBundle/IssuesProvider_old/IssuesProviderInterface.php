<?php
namespace ScrumBoardItBundle\IssuesProvider;

/**
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssuesProviderInterface
{

    /**
     * Retrieve issues.
     *
     * @return ScrumBoardItBundle\Collection\IssuesCollectionInterface
     */
    public function getIssues();
}
