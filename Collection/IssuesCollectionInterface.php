<?php

namespace CanalTP\ScrumBoardItBundle\Collection;

use CanalTP\PostitBundle\Entitie\IssueInterface;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface IssuesCollectionInterface {
    /**
     * Add an element to the collection
     * @param \CanalTP\PostitBundle\Entitie\IssueInterface $item
     * @return int Index of the item
     */
    public function add(IssueInterface $item);
    
    /**
     * Remove an element from the collection
     * @param int $index
     * @return \CanalTP\PostitBundle\Entitie\IssueInterface Removed item
     */
    public function remove($index);
    
    /**
     * Get an item from the collection
     * @param int $index
     * @return \CanalTP\PostitBundle\Entitie\IssueInterface Requested item
     */
    public function get($index);
}
