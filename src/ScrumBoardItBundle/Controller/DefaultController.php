<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
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
        return new Response("<html><body>index</body></html>");
    }

    /**
     * @Route("/login", name="login")
     * 
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        $session = $request->getSession();
        $error = '';
        
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        }

        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
                    'last_username' => $session->get(Security::LAST_USERNAME),
                    'error' => $error,
        ));
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
        
    }
    
    /**
     * @Route("/login_check", name="login_check")
     * 
     * @return type
     */
    public function loginCheckAction() {
        dump($this->get('security.authorization_checker')->isGranted('ROLE_AUTHENTICATED'));
       if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTHENTICATED')) {
            return $this->redirect($this->generateUrl('home'));
        }
        else {
            return $this->redirect($this->generateUrl('login'));
        }
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
     * @Secure("has_role('ROLE_AUTHENTICATED')")
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
