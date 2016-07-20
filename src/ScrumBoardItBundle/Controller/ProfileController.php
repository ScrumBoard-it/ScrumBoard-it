<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Controller for Profile page.
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     * @Security("has_role('IS_AUTHENTICATED_FULLY')")
     */
    public function profileAction(Request $request)
    {
        if($this->getUser()->getUsername() === 'visitor') {
            return $this->redirect('home');
        }
        $page = $request->get('page', 'general');
        $profileService = $this->get('profile.service');
        $form = $profileService->getForm($request, $this->getUser(), $page);
        $info = null;
        $error = null;
        if ($form->isValid() && $form->isSubmitted()) {
            try {
                $profileService->persist($form, $this->getUser());
                $info = 'Les modifications ont été enregistrées.';
            } catch (\Exception $e) {
                $error = $e;
            }
        }

        return $this->render('ScrumBoardItBundle:Security:profile.html.twig', array(
            'form' => $form->createView(),
            'info' => $info,
            'error' => $error,
            'include' => $profileService->getIncludeTemplate($page),
        ));
    }
}
