<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;

/**
 * controller of navigation.
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="index")
     * @Route("/logout", name="logout")    * 
     * @return Response
     */
    public function indexAction() {
        return $this->redirect($this->generateUrl('login_check'));
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function home() {
        $issues = $this->container->get('jira.api')->getIssues(510);
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
                    'issues' => $issues
        ));
    }

    /**
     * @Route("/print", name="print")
     * 
     * @param Request $request
     * @return Response
     */
    public function printAction(Request $request) {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');

        return $this->render(
                        'ScrumBoardItBundle:Print:tickets.html.twig', array(
                    'issues' => $service->getIssues($selected),
                        )
        );
    }

    /**
     * @Route("/flag/add", name="add_flag")
     * 
     * @param Request $request
     * @return Response
     */
    public function addFlagAction(Request $request) {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);

        return $this->redirect($this->generateUrl('index'));
    }

}
