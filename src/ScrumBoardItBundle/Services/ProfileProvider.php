<?php

namespace ScrumBoardItBundle\Services;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Form;
use ScrumBoardItBundle\Entity\Mapping\Favorites;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Profile Provider.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ProfileProvider
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var array
     */
    private $persistServices;

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session, FormFactory $formFactory, array $persistServices)
    {
        $this->session = $session;
        $this->formFactory = $formFactory;
        $this->persistServices = $persistServices;
    }

    /**
     * Return form for the profile page.
     *
     * @param Request $request
     * @param string  $page
     *
     * @return FormInterface
     */
    public function getForm(Request $request, $page)
    {
        switch ($page) {
            case 'jira':
                $service = $this->persistServices['jira_configuration'];
                $data = $service->getEntity();
                break;
            case 'favorites':
                $service = $this->persistServices['favorites'];
                $data = $service->getEntity();
                break;
            case 'general':
            default:
                $service = $this->persistServices['general'];
                $data = null;
                break;
        }
        $options = array(
            'data' => $data,
        );
        $form = $this->formFactory->create($service->getFormConfiguration()['form'], $service->getFormConfiguration()['entity'], $options);
        $form->handleRequest($request);

        return $form;
    }

    public function submitForm(Form $form)
    {
        $service = explode('.', $form->getName())[0];
        $this->persistServices[$service]->flushEntity($form->getData());

        return $form;
    }

    /**
     * Return template name for the profile page.
     *
     * @param string $page
     *
     * @return string
     */
    public function getIncludeTemplate($page)
    {
        switch ($page) {
            case 'jira':
                $include = 'jira';
                break;
            case 'favorites':
                $include = 'favorites';
                break;
            case 'general':
            default:
                $include = 'general';
                break;
        }

        return 'ScrumBoardItBundle:Profile:'.$include.'Profile.html.twig';
    }
}
