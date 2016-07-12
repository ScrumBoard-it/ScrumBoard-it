<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of InvalidApiResponseException.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class BadConnectionException extends \Exception
{
    public function __construct()
    {
        $message = sprintf('Le service distant ne répond pas. Veuillez réessayer plus tard.');
        parent::__construct($message);
    }
}
