<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
use ScrumBoardItBundle\Entity\Search\SearchEntity;

/**
 * controller of navigation.
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="index") *
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->redirect('login');
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function home(Request $request)
    {
        $service = $this->get($this->getUser()
            ->getConnector() . '.api');
        
        $searchFilters = $service->getSearchFilters($request);
        $issues = $service->searchIssues($searchFilters);
        
        $searchEntity = new SearchEntity($searchFilters);
        $form = $this->createForm($service->getFormType(), $searchEntity);
        
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'issues' => $issues
        ));
    }

    /**
     *
     * @Route("/print", name="print")
     *
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request            
     * @return Response
     */
    public function printAction(Request $request)
    {
        $service = $this->get($this->getUser()
            ->getConnector() . '.api');
        $selected = $request->request->get('issues');
        
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getSelectedIssues($request, $selected)
        ));
    }

    /**
     * @Route("/flag", name="add_flag")
     *
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request            
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $service = $this->get($this->getUser()
            ->getConnector() . '.api');
        $selected = $request->request->get('issues');
        $service->addFlag($request, $selected);
        
        return $this->redirect('home');
    }
}
