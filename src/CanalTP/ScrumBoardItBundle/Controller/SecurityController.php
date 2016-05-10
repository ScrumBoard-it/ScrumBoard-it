<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * controller of connexion to jira.
 */
class SecurityController extends Controller {

    /**
     * Route("/login", name="login")
     * Route("/login_check", name="login_check")
     * Route("/logout", name="logout")
     * 
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        //$request = $this->getRequest();
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

}
