<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * controller of navigation.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="canal_tp_postit_homepage")
     * 
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $manager = $this->get('service.manager');
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
    
    /**
     * @Route("/login", name="login")
     * 
     * @return Response
     */
    public function loginAction() {
        return new Response('<html><body>Connecté</body></html>');
    }

    /**
     * @Route("/print", name="canal_tp_postit_print")
     * 
     * @param Request $request
     * @return Response
     */
    public function printAction(Request $request)
    {
        $manager = $this->container->get('service.manager');
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

    /**
     * @Route("/flag/add", name="canal_tp_postit_add_flag")
     * 
     * @param Request $request
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);

        return $this->redirect($this->generateUrl('canal_tp_postit_homepage'));
    }
}
