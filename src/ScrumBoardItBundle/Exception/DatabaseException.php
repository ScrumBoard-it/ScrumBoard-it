<?php

namespace ScrumBoardItBundle\Exception;

/**
 * Description of DatabaseException.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class DatabaseException extends \Exception
{
    public function __construct()
    {
        $message = sprintf('Erreur lors de la connexion à la base de données. Veuillez réessayer.');
        parent::__construct($message);
    }
}
