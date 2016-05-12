<?php
namespace ScrumBoardItBundle\Exception;

/**
 * Description of ClassNotFoundException.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class ClassNotFoundException extends \Exception
{

    public function __construct($class)
    {
        $message = sprintf('Class %s not found.', $class);
        parent::__construct($message);
    }
}
