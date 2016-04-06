<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * controller of navigation.
 */
class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();

        $issuesSearch = $request->get('issues_search');
        if (!empty($issuesSearch['board'])) {
            $service->setBoardId($issuesSearch['board']);
        }
        if (!empty($issuesSearch['sprint'])) {
            $service->setSprintId($issuesSearch['sprint']);
        }

        $form = $this->createForm('issues_search');

        return $this->render(
            'CanalTPScrumBoardItBundle:Default:index.html.twig',
            array(
                'form' => $form->createView(),
                'issues' => $service->getIssues(),
            )
        );
    }

    public function printAction(Request $request)
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');

        return $this->render(
            'CanalTPScrumBoardItBundle:Print:tickets.html.twig',
            array(
                'issues' => $service->getIssues($selected),
            )
        );
    }

    public function addFlagAction(Request $request)
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);

        return $this->redirect($this->generateUrl('canal_tp_postit_homepage'));
    }
}
