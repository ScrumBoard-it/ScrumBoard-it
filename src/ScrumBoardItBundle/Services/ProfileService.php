<?php

namespace ScrumBoardItBundle\Services;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Profile\GeneralProfileType;
use ScrumBoardItBundle\Form\Type\Profile\JiraProfileType;
use ScrumBoardItBundle\Entity\Profile\JiraProfileEntity;
use ScrumBoardItBundle\Entity\Profile\GeneralProfileEntity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use ScrumBoardItBundle\Entity\Mapping\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle;

class ProfileService
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderService;

    public function __construct(FormFactory $formFactory, EntityManager $em, TokenStorage $token, EncoderFactoryInterface $encoderService)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->user = $token->getToken()->getUser();
        $this->encoderService = $encoderService;
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

    public function persist(Form $form)
    {
        $data = $form->getData();
        switch ($form->getName()) {
            case 'general_profile':
                $this->persistGeneralProfile($data);
                break;
            case 'jira_profile':
                $this->persistJiraProfile($data);
                break;
            default: break;
        }
    }

    private function persistGeneralProfile($data)
    {
        $encoder = $this->encoderService->getEncoder($this->user);
        $user = $this->em->getRepository('ScrumBoardItBundle:Mapping\User')
            ->find($this->user->getId());
        if ($encoder->isPasswordValid($user->getPassword(), $data->getOldPassword(), $user->getSalt())) {
            $user->setPassword($encoder->encodePassword($data->getNewPassword(), $user->getSalt()));
            $this->user->setPassword($user->getPassword());
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                throw $e;
                // throw new \Exception('Une erreur est survenue, veuillez rééssayer.');
            }
        } else {
            throw new \Exception('Mot de passe intial erroné.');
        }
    }

    private function persistJiraProfile($data)
    {
        try {
            $jiraConfiguration = $this->em->getRepository('ScrumBoardItBundle:Mapping\JiraConfiguration')
                ->findOneBy(array(
                    'userId' => $this->user->getId(),
            ));
            $jiraConfiguration->setUrl($data->getUrl());
            $jiraConfiguration->setPrintedTag($data->getPrintedTag());
            $jiraConfiguration->setComplexityField($data->getComplexityField());
            $jiraConfiguration->setBusinnessValueField($data->getBusinnessValueField());
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception('Une erreur est survenue, veuillez rééssayer.');
        }
    }
}
