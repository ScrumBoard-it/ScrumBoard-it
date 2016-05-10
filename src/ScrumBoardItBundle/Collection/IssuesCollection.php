<?php
namespace ScrumBoardItBundle\Collection;

<<<<<<< a0df371f1f85b80f2e1618d9b067030d71cdb1fa:src/CanalTP/ScrumBoardItBundle/Collection/IssuesCollection.php
use CanalTP\ScrumBoardItBundle\Entity\IssueInterface;
=======
use ScrumBoardItBundle\Entitie\IssueInterface;
>>>>>>> 15/04/2016 Refonte sécurité (mise à jour des services):src/ScrumBoardItBundle/Collection/IssuesCollection.php

/**
 * Description of IssuesCollection.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class IssuesCollection implements IssuesCollectionInterface, \Iterator
{

    public $collection = array();

    private $position = 0;

    public function add(IssueInterface $item)
    {
        $this->collection[] = $item;
        end($this->collection);
        
        return key($this->collection);
    }

    public function get($index)
    {
        return $this->collection[$index];
    }

    public function remove($index)
    {
        $item = $this->get($index);
        unset($this->collection[$index]);
        
        return $item;
    }

    public function current()
    {
        return $this->collection[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++ $this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->collection[$this->position]);
    }
}
