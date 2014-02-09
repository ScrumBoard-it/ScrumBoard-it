<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrintController extends Controller
{
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
                'issues' => $issues
            )
        );
    }
}
