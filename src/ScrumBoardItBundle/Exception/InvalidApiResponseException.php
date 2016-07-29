<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of InvalidApiResponseException.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class InvalidApiResponseException extends \Exception
{
    public function __construct()
    {
        $message = sprintf('Le service distant a rencontré une erreur. Veuillez réessayer.');
        parent::__construct($message);
    }
}
