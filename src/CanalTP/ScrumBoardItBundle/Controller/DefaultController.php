<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Security;
use CanalTP\ScrumBoardItBundle\Jira\User\JiraRest;

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
     * @Route("/logout", name="logout")
     * 
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        $session = $request->getSession();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('canal_tp_postit_homepage'));
        }
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }
        return $this->render('CanalTPScrumBoardItBundle:Security:login.html.twig', array(
                    'last_username' => $session->get(Security::LAST_USERNAME),
                    'error' => $error,
        ));
    }
    
    //Fonction temporaire
    /**
     * @Route("/login_check", name="login_check")
     * 
     * @param Request request
     * @return Response
     */
    public function loginCheckAction(Request $request) {
        $login = $request->request->get('_username');
        $password = $request->request->get('_password');
        $data=JiraRest::authenticate($login,$password);
        var_dump($data); die;
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
