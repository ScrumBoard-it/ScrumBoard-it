<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of InvalidApiResponseException.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class InvalidApiResponseException extends \Exception
{
    public function __construct()
    {
        $message = sprintf('Le service distant ne répond pas ou a rencontré une erreur. Veuillez réessayer.');
        parent::__construct($message);
    }
}
