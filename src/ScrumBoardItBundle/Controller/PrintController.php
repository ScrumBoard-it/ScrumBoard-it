<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Controller to print tickets.
 */
class PrintController extends Controller
{
    /**
     * @Route("/print/base", name="print_patron")
     *
     * @return Response
     */
    public function baseAction()
    {
        return $this->render('ScrumBoardItBundle:Print:base.html.twig');
    }

    /**
     * Launch the Print Manager.
     *
     * @param array $issues
     *
     * @return Response
     */
    public function ticketsAction($issues)
    {
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $issues,
        ));
    }
}
