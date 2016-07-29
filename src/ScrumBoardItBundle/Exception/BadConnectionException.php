<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of BadConnectionException.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class BadConnectionException extends \Exception
{
    public function __construct()
    {
        $message = sprintf('Le service distant ne répond pas. Veuillez réessayer plus tard.');
        parent::__construct($message);
    }
}
