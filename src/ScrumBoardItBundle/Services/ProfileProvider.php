<?php

namespace ScrumBoardItBundle\Services;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Profile\GeneralProfileType;
use ScrumBoardItBundle\Form\Type\Profile\JiraProfileType;
use ScrumBoardItBundle\Entity\Profile\GeneralProfileEntity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Form;
use ScrumBoardItBundle\Entity\Mapping\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle;
use ScrumBoardItBundle\Entity\Mapping\JiraConfiguration;
use ScrumBoardItBundle\Form\Type\ConfigurationType;

/**
 * Profile Provider.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ProfileProvider
{
    /**
     * @var string
     */
    const DEFAULT_TAG = 'Post-it';

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderService;

    public function __construct(FormFactory $formFactory, EntityManager $em, EncoderFactoryInterface $encoderService)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
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
    public function getForm(Request $request, User $user, $page)
    {
        switch ($page) {
            case 'jira':
                $formType = JiraProfileType::class;
                $entity = JiraConfiguration::class;
                $options = array(
                    'data' => $this->getJiraConfiguration($user), );
                break;
            case 'general':
            default:
                $formType = GeneralProfileType::class;
                $entity = GeneralProfileEntity::class;
                $options = array();
                break;
        }
        $form = $this->formFactory->create($formType, new $entity(), $options);
        $form->handleRequest($request);

        return $form;
    }

    /**
     * Persist data form.
     *
     * @param Form $form
     * @param User $user
     */
    public function persist(Form $form, User $user)
    {
        $data = $form->getData();
        switch ($form->getName()) {
            case 'general_profile':
                $this->persistGeneralProfile($data, $user);
                break;
            case 'jira_profile':
                $this->persistJiraProfile($data);
                break;
            default: break;
        }
    }

    /**
     * Persist General data form.
     *
     * @param \stdClass $data
     * @param User      $user
     *
     * @throws \Exception
     */
    private function persistGeneralProfile($data, User $user)
    {
        $encoder = $this->encoderService->getEncoder($user);
        $user = $this->em->getRepository('ScrumBoardItBundle:Mapping\User')
            ->find($user->getId());
        if ($encoder->isPasswordValid($user->getPassword(), $data->getOldPassword(), $user->getSalt())) {
            $user->setPassword($encoder->encodePassword($data->getNewPassword(), $user->getSalt()));
            $user->setPassword($user->getPassword());
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                throw new \Exception('Une erreur est survenue, veuillez rééssayer.');
            }
        } else {
            throw new \Exception('Mot de passe intial erroné.');
        }
    }

    /**
     * Persist Jira data form.
     *
     * @param JiraConfiguration $jiraConfiguration
     *
     * @throws \Exception
     */
    private function persistJiraProfile(JiraConfiguration $jiraConfiguration)
    {
        if (empty($jiraConfiguration->getPrintedTag())) {
            $jiraConfiguration->setPrintedTag(self::DEFAULT_TAG);
        }
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception('Une erreur est survenue, veuillez rééssayer.');
        }
    }

    /**
     * Jira configuration getter.
     *
     * @param User $user
     *
     * @throws \Exception
     *
     * @return object
     */
    public function getJiraConfiguration(User $user)
    {
        try {
            return $this->em->getRepository('ScrumBoardItBundle:Mapping\JiraConfiguration')
            ->findOneBy(array(
                'userId' => $user->getId(),
            ));
        } catch (\Exception $e) {
            throw new \Exception("Nous n'avons pas pu récupérer votre profil, veuillez rééssayer.");
        }
    }

    /**
     * Register new user.
     *
     * @param User $user
     */
    public function register(User $user)
    {
        $password = $this->encoderService->getEncoder($user)
            ->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
        $user->addRole('IS_AUTHENTICATED_FULLY');
        $this->em->persist($user);
        $this->em->flush();

        $jiraConfiguration = new JiraConfiguration();
        $userId = $this->em->getRepository('ScrumBoardItBundle:Mapping\User')
            ->findOneByUsername($user->getUsername())
            ->getId();
        $jiraConfiguration->setUserId($userId);
        $this->em->persist($jiraConfiguration);
        $this->em->flush();
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
            case 'general':
            default:
                $include = 'general';
                break;
        }

        return 'ScrumBoardItBundle:Profile:'.$include.'Profile.html.twig';
    }

    /**
     * Set and return new template configuration.
     *
     * @param Request $request
     * @param User    $user
     *
     * @return User
     */
    public function setTemplateConfiguration(Request $request, User $user)
    {
        $configurationForm = $this->formFactory->create(ConfigurationType::class, $user);
        $configurationForm->handleRequest($request);
        $user->setConfiguration(array(
            'user_story' => $configurationForm->get('user_story')->getData(),
            'sub_task' => $configurationForm->get('sub_task')->getData(),
            'poc' => $configurationForm->get('poc')->getData(),
        ));
        $this->em->flush();

        return $configurationForm;
    }

    /**
     * Return an array of the user database configurations.
     *
     * @param User $user
     *
     * @return array
     */
    public function getUserConfiguration(User $user)
    {
        return array(
            'jira' => $this->getJiraConfiguration($user),
        );
    }
}
