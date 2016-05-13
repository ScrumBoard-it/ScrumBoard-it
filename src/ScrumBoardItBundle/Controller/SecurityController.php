<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     *
     * @param Request $request            
     * @return Response
     */
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTHENTICATED')) {
            return $this->redirectToRoute('home');
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {}

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {}
}
