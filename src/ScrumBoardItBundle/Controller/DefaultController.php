<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;
use ScrumBoardItBundle\Form\Type\Search\JiraSearchType;
use ScrumBoardItBundle\Form\Type\Search\GithubSearchType;
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
        return $this->redirect($this->generateUrl('login'));
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function home(Request $request)
    {
        $results = $this->issuesAction($request);
        
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
            'form' => $results['form']->createView(),
            'issues' => $results['issues']
        ));
    }

    /**
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
        $search = new SearchEntity($searchFilters);
        
        switch ($this->getUser()->getConnector()) {
            case 'jira':
                $form = $this->createForm(JiraSearchType::class, $search);
                break;
            case 'github':
                $form = $this->createForm(GithubSearchType::class, $search);
                break;
        }
        
        $issues = $service->searchIssues($searchFilters);
        
        $results = array(
            'form' => $form,
            'issues' => $issues
        );
        
        return $results;
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
        $service = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        $selected = $request->request->get('issues');
        
        return $this->render('ScrumBoardItBundle:Print:tickets.html.twig', array(
            'issues' => $service->getSelectedIssues($request, $selected)
        ));
    }

    /**
     * @Route("/flag/add", name="add_flag")
     *
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     *
     * @param Request $request            
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $service = $this->container->get($this->getUser()
            ->getConnector() . '.api');
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);
        
        return $this->redirect($this->generateUrl('index'));
    }
}
