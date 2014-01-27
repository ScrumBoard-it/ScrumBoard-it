<?php

namespace CanalTP\PostitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrintController extends Controller
{
    public function baseAction()
    {
        return $this->render(
            'CanalTPPostitBundle:Print:base.html.twig'
        );
    }

    public function ticketsAction($issues)
    {
        return $this->render(
            'CanalTPPostitBundle:Print:tickets.html.twig',
            array(
                'issues' => $issues
            )
        );
    }
}
