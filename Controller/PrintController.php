<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Controller to print tickets.
 */
class PrintController extends Controller
{    
    /**
     * @Route("/print/base", name="canal_tp_postit_print_patron")
     * 
     * @return Response
     */
    public function baseAction()
    {
        return $this->render(
            'CanalTPScrumBoardItBundle:Print:base.html.twig'
        );
    }

    public function ticketsAction($issues)
    {
        return $this->render(
            'CanalTPScrumBoardItBundle:Print:tickets.html.twig',
            array(
                'issues' => $issues,
            )
        );
    }
}
