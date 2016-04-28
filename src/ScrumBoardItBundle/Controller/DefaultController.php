<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use ScrumBoardItBundle\Form\Type\Search\JiraSearchType;
use ScrumBoardItBundle\Entity\Search\JiraSearch;

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
        return $this->redirect($this->generateUrl('login_check'));
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function home()
    {
        $api = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        $jiraSearch = new JiraSearch();
        $jiraSearch->setProjects($api->getProjects());
        $form = $this->createForm(JiraSearchType::class, $jiraSearch);
        
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'issues' => null
        ));
    }

    /**
     * Ajax call to refresh form and issues
     *
     * @Route("/home/issues", name="issues")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @method ({"GET", "POST"})
     *        
     * @param Request $request            
     */
    public function issuesAction(Request $request)
    {
        // if ($request->isXMLHttpRequest()) {
        $service = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        $searchFilters = $service->getSearchFilters($request);
        $issues = $service->getIssues($searchFilters);
        
        $results = array(
            'search_filters' => $searchFilters,
            'issues' => $issues
        );
        
        return new JsonResponse($results);
        // }
        // return new Response("Error: Request Type (Ajax request expected)", 400);
    }

    /**
     * @Route("/print", name="print")
     *
     * @param Request $request            
     * @return Response
     */
    public function printAction(Request $request)
    {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getIssues($selected)
        ));
    }

    /**
     * @Route("/flag/add", name="add_flag")
     *
     * @param Request $request            
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);
        
        return $this->redirect($this->generateUrl('index'));
    }
}
