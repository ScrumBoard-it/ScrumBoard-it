<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of InvalidOptionException.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class InvalidOptionException extends \Exception
{
    public function __construct($option)
    {
        $message = sprintf('Option %s invalide.', $option);
        parent::__construct($message);
    }
}
