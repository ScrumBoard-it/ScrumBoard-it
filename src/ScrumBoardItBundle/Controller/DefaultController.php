<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
=======
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
>>>>>>> brieuc/post-it-manager
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
<<<<<<< HEAD
    public function home(Request $request)
    {
        $results = $this->issuesAction($request);
        
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
            'form' => $results['form']->createView(),
            'issues' => $results['issues']
=======
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
>>>>>>> brieuc/post-it-manager
        ));
    }

    /**
<<<<<<< HEAD
     * Return form and issues from the request
     *
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request            
     * @return array
     */
    private function issuesAction(Request $request)
    {
        $service = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        $searchFilters = $service->getSearchFilters($request);
        
        switch ($this->getUser()->getConnector()) {
            case 'jira':
                $jiraSearch = new JiraSearch($searchFilters);
                $form = $this->createForm(JiraSearchType::class, $jiraSearch);
                break;
        }
        
        $issues = $service->searchIssues($searchFilters);
        
        $results = array(
            'form' => $form,
            'issues' => $issues
        );
        
        return $results;
=======
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
        
        $config = array(
            'search_filters' => $searchFilters,
            'issues' => $issues
        );
        
        return new JsonResponse($config);
        // }
        // return new Response("Error: Request Type (Ajax request expected)", 400);
>>>>>>> brieuc/post-it-manager
    }

    /**
     * @Route("/print", name="print")
     *
     * @param Request $request            
     * @return Response
     */
    public function printAction(Request $request)
    {
<<<<<<< HEAD
        $service = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        $selected = $request->request->get('issues');
        
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getSelectedIssues($request, $selected)
=======
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getIssues($selected)
>>>>>>> brieuc/post-it-manager
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
