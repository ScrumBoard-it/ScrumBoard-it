<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ScrumBoardItBundle\Entity\Search\SearchEntity;
use ScrumBoardItBundle\Form\Type\ConfigurationType;
use ScrumBoardItBundle\Form\Type\BugtrackerType;
use ScrumBoardItBundle\Entity\User;
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
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY', 'IS_CONFIGURED')) {
            return $this->redirect('home');
        }

        return $this->render('ScrumBoardItBundle:Default:presentation.html.twig');
    }

    /**
     * @Route("/bugtracker/{api}", name="bugtracker", defaults={"api" = null})
     * @Security("has_role('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bugtrackerAction($api)
    {
        if($api) {
            $this->getUser()->setApi($api.'.api');
            return $this->redirect($this->generateUrl('hwi_oauth_service_redirect', array(
                'service' => $api
            )));
        }

        return $this->render('ScrumBoardItBundle:Default:bugtracker.html.twig');
    }

    /**
     * @Route("/home", name="home")
     * @Security("has_role('IS_AUTHENTICATED_FULLY','IS_CONFIGURED')")
     */
    public function homeAction(Request $request)
    {
        if (!empty($this->getUser()->getApi())) {
            $apiService = $this->get($this->getUser()->getApi());

            $user = $apiService->getDatabaseUser();
            $configurationForm = $this->createForm(ConfigurationType::class, $user);
            $configurationForm->handleRequest($request);
            $user->setConfiguration(array(
                'user_story' => $configurationForm->get('user_story')->getData(),
                'sub_task' => $configurationForm->get('sub_task')->getData(),
                'poc' => $configurationForm->get('poc')->getData(),
            ));
            $this->getDoctrine()->getManager()->flush();

            $apiSearch = $apiService->getSearchFilters($request);
            $issues = $apiService->searchIssues($apiSearch['search_filters']);
            $form = $this->createForm($apiService->getFormType(), new SearchEntity($apiSearch['search_filters']));

            return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
                'form' => $form->createView(),
                'configuration_form' => $configurationForm->createView(),
                'issues' => $issues,
                'error' => isset($apiSearch['error']) ? $apiSearch['error'] : null,
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
        $user = $apiService->getDatabaseUser();
        $configuration = $user->getConfiguration();

        $templateForm = $this->createForm(ConfigurationType::class);
        $templates = array(
          'user_story' => $templateForm->get('user_story')[$configuration['user_story']],
          'sub_task' => $templateForm->get('sub_task')[$configuration['sub_task']],
          'poc' => $templateForm->get('poc')[$configuration['poc']],
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
        try {
            $apiService = $this->get($this->getUser()->getApi());
            $selected = $request->request->get('issues');
            $apiService->addFlag($request, $selected);
        } catch (\Exception $e) {
            $this->get('logger')->error('Error during insertion of the printed tag');
        }

        return $this->redirect('home');
    }
}
