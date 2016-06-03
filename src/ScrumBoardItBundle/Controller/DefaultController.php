<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
use ScrumBoardItBundle\Entity\Search\SearchEntity;
use ScrumBoardItBundle\Form\Type\TemplateType;

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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTHENTICATED')) {
            return $this->redirect('home');
        }

        return $this->render('ScrumBoardItBundle:Default:presentation.html.twig');
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function homeAction(Request $request)
    {
        $service = $this->get($this->getUser()->getConnector().'.api');
        $session = $request->getSession();

        $searchFilters = $service->getSearchFilters($request);
        $issues = $service->searchIssues($searchFilters);

        $searchEntity = new SearchEntity($searchFilters);
        $form = $this->createForm($service->getFormType(), $searchEntity);
        $template = $this->buildTemplate($session, $request);

        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'template' => $template->createView(),
            'issues' => $issues
        ));
    }

    /**
     *
     * @param TemplateType $template
     * @param Session $session
     * @param Request $request
     * @return TemplateType
     */
    public function buildTemplate($session, Request $request)
    {
        $template = $this->createForm(TemplateType::class);
        if(null !== $session->get('template')){
            foreach ($template as $indice => $data) {
                $template->get($indice) -> setData($session->get('template')[$indice]);
            }
        }else{
            foreach ($template as $indice => $data) {
                $template->get($indice) -> setData(1);
            }
        }
        $template->handleRequest($request);

        $session->set('template', array(
            'userStory' => $template->get('userStory')->getData(),
            'subTask' => $template->get('subTask')->getData(),
            'poc' => $template->get('poc')->getData()
        ));

        return $template;
    }

    /**
     *
     * @Route("/print", name="print")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function printAction(Request $request)
    {
        $service = $this->get($this->getUser()->getConnector().'.api');
        $selected = $request->request->get('issues');

        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getSelectedIssues($request, $selected),
        ));
    }

    /**
     * @Route("/flag", name="add_flag")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $service = $this->get($this->getUser()->getConnector().'.api');
        $selected = $request->request->get('issues');
        $service->addFlag($request, $selected);

        return $this->redirect('home');
    }

    /**
     * @Route("/discover", name="discover")
     */
    public function visitorAction()
    {
        // No action, Guard authenticates the user as a visitor and redirect to home
    }
}
