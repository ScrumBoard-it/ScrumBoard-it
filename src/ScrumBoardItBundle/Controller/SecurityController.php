<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use ScrumBoardItBundle\Form\Type\LoginType;
use ScrumBoardItBundle\Form\Type\RegistrationType;
use ScrumBoardItBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Controller of security.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect('home');
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/registration", name="registration")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registrationAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect('home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    return $this->redirect('login');
                } catch (UniqueConstraintViolationException $e) {
                    $error = new UniqueConstraintViolationException("Désolé, ce nom d'utilisateur est déjà utilisé...");
                } catch (\Exception $e) {
                    $error = new \Exception("Une erreur s'est produite, veuillez réessayer.");
                }
            }
        }

        return $this->render('ScrumBoardItBundle:Security:registration.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
