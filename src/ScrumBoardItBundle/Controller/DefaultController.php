<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secure;

/**
 * controller of navigation.
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="index")
     * @Route("/logout", name="logout")    * 
     * @return Response
     */
    public function indexAction() {
        return $this->redirect($this->generateUrl('login_check'));
    }

    /**
     * @Route("/home", name="home")
     * @Secure("has_role('ROLE_AUTHENTICATED')")
     */
    public function home() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://jira.canaltp.fr/rest/api/latest/search?jql=sprint=510");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $this->getUser()->getHash()]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($content, true);
        $issues = array();
        for ($i = 0; $i < $data['total']; $i++) {
            $issue;
            if ($data['issues'][$i]['fields']['issuetype']['id'] == 5) {
                $issue = new \ScrumBoardItBundle\Entity\Issue\SubTask();
            } else {
                $issue = new \ScrumBoardItBundle\Entity\Issue\Task();
            }
            $issue->setProject($data['issues'][$i]['key']);
            $issue->setTitle($data['issues'][$i]['fields']['summary']);
            if ($issue->getType() === 'task') {
                $issue->setComplexity($data['issues'][$i]['fields']['customfield_11108']);
            }
            array_push($issues, $issue);
        }
        return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
                    'issues' => $issues
        ));
        /*return $this->render('ScrumBoardItBundle:Default:index.html.twig', array(
                    'issues' => null
        ));*/
    }

    /**
     * @Route("/print", name="print")
     * 
     * @param Request $request
     * @return Response
     */
    public function printAction(Request $request) {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');

        return $this->render(
                        'ScrumBoardItBundle:Print:tickets.html.twig', array(
                    'issues' => $service->getIssues($selected),
                        )
        );
    }

    /**
     * @Route("/flag/add", name="add_flag")
     * 
     * @param Request $request
     * @return Response
     */
    public function addFlagAction(Request $request) {
        $manager = $this->container->get('service.manager');
        $service = $manager->getService();
        /* @var $service ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);

        return $this->redirect($this->generateUrl('index'));
    }

}
