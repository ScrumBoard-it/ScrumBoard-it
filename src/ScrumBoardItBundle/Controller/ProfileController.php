<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $page = !empty($request->get('page')) ? $request->get('page') : 'general';
        $profileService = $this->get('profile_processor');
        $form = $profileService->getForm($request, $page);

        return $this->render('ScrumBoardItBundle:Security:profile.html.twig', array(
            'form' => $form->createView(),
            'include' => $page,
        ));
    }

    /**
     * @Route("/profile/general", name="profile_general")
     * @Security("has_role('IS_AUTHENTICATED_FULLY')")
     */
    public function generalProfileAction(Request $request)
    {
        $profileService = $this->get('profile_processor');
        $form = $profileService->getForm($request, 'general');

        return new JsonResponse(array(
            'contain' => $this->renderView('ScrumBoardItBundle:Profile:generalProfile.html.twig', array(
                'form' => $form->createView(),
            )),
        ), 200);
    }

    /**
     * @Route("/profile/jira", name="profile_jira")
     * @Security("has_role('IS_AUTHENTICATED_FULLY')")
     */
    public function jiraProfileAction(Request $request)
    {
        $profileService = $this->get('profile_processor');
        $form = $profileService->getForm($request, 'jira');

        return new JsonResponse(array(
            'contain' => $this->renderView('ScrumBoardItBundle:Profile:jiraProfile.html.twig', array(
                'form' => $form->createView(),
            )),
        ), 200);
    }
}
