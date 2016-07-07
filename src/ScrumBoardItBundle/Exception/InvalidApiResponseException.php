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
        $message = sprintf('Erreur lors de la connexion au service distant.');
        parent::__construct($message);
    }
}
