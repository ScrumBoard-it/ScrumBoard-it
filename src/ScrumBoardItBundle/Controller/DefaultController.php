<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ScrumBoardItBundle\Entity\Search\SearchEntity;
use ScrumBoardItBundle\Form\Type\ConfigurationType;
use ScrumBoardItBundle\Entity\Configuration;
use ScrumBoardItBundle\Form\Type\BugtrackerType;

/**
 * Controller of navigation.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_CONFIGURED')) {
            return $this->redirect('home');
        }

        return $this->render('ScrumBoardItBundle:Default:presentation.html.twig');
    }

    /**
     * @Route("/bugtracker", name="bugtracker")
     * @Security("has_role('IS_AUTHENTICATED')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bugtrackerAction(Request $request)
    {
        $form = $this->createForm(BugtrackerType::class);
        $form->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('ScrumBoardItBundle:Security:bugtracker.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/home", name="home")
     * @Security("has_role('IS_AUTHENTICATED','IS_CONFIGURED')")
     */
    public function homeAction(Request $request)
    {
        if (!empty($this->getUser()->getApi())) {
            $apiService = $this->get($this->getUser()->getApi());
            $session = $request->getSession();

            $searchFilters = $apiService->getSearchFilters($request);
            $issues = $apiService->searchIssues($searchFilters);

            $form = $this->createForm($apiService->getFormType(), new SearchEntity($searchFilters));

            $sessionConfiguration = new Configuration($request);
            $configurationForm = $this->createForm(ConfigurationType::class, $sessionConfiguration);
            $configurationForm->handleRequest($request);
            $session->set('template', array(
                'user_story' => $configurationForm->get('user_story')->getData(),
                'sub_task' => $configurationForm->get('sub_task')->getData(),
                'poc' => $configurationForm->get('poc')->getData(),
            ));

            return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
                'form' => $form->createView(),
                'configuration_form' => $configurationForm->createView(),
                'issues' => $issues,
            ));
        }

        return $this->redirect('bugtracker');
    }

    /**
     * @Route("/print", name="print")
     * @Security("has_role('IS_CONFIGURED')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function printAction(Request $request)
    {
        $apiService = $this->get($this->getUser()->getApi());
        $selected = $request->request->get('issues');

        $session = $request->getSession();
        $templateForm = $this->createForm(ConfigurationType::class);
        $templateForm->getData();
        $templates = array(
          'user_story' => $templateForm->get('user_story')[$session->get('template')['user_story']],
          'sub_task' => $templateForm->get('sub_task')[$session->get('template')['sub_task']],
          'poc' => $templateForm->get('poc')[$session->get('template')['poc']],
        );

        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $apiService->getSelectedIssues($request, $selected),
            'templates' => $templates,
        ));
    }

    /**
     * @Route("/flag", name="add_flag")
     * @Security("has_role('IS_CONFIGURED')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $apiService = $this->get($this->getUser()->getApi());
        $selected = $request->request->get('issues');
        $apiService->addFlag($request, $selected);

        return $this->redirect('home');
    }
}
