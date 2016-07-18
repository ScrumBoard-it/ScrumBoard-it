<?php

namespace ScrumBoardItBundle\Services;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Profile\GeneralProfileType;
use ScrumBoardItBundle\Form\Type\Profile\JiraProfileType;
use ScrumBoardItBundle\Entity\Profile\JiraProfileEntity;
use ScrumBoardItBundle\Entity\Profile\GeneralProfileEntity;
use Symfony\Component\Form\FormInterface;

class ProfileService
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
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
                $formType = JiraProfileType::class;
                $entity = JiraProfileEntity::class;
                break;
            case 'general':
            default:
                $formType = GeneralProfileType::class;
                $entity = GeneralProfileEntity::class;
                break;
        }
        $form = $this->formFactory->create($formType, new $entity());
        $form->handleRequest($request);

        return $form;
    }
}
