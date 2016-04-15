<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Security;

/**
 * controller of navigation.
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="index")
     * 
     * @return Response
     */

    public function indexAction() {
        //A changer : redirection automatique à faire
        return $this->redirect($this->generateUrl('login'));
    }

    /**
     * @Route("/login", name="login")
     * @Route("/login_check", name="login_check")
     * @Route("/logout", name="logout")
     * 
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        $session = $request->getSession();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            
            return $this->redirect($this->generateUrl('home'));
        }
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
                    'last_username' => $session->get(Security::LAST_USERNAME),
                    'error' => $error,
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
     * @Route("/home", name="home")
     */
    public function home() {
        return new Response("<html><body>Connecté</body></html>");
    }

    /**
     * @Route("/flag/add", name="canal_tp_postit_add_flag")
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
