<?php

namespace ScrumBoardItBundle\Services\Persist;

use ScrumBoardItBundle\Form\Type\Profile\GeneralProfileType;
use ScrumBoardItBundle\Entity\Profile\GeneralProfileEntity;
use ScrumBoardItBundle\Exception\DatabaseException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle\Form\Type\ConfigurationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use ScrumBoardItBundle\Entity\Mapping\User;

class General extends AbstractPersistClass
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderService;

    public function __construct(TokenStorage $token, EntityManager $entityManager, EncoderFactoryInterface $encoderService)
    {
        parent::__construct($token, $entityManager);
        $this->encoderService = $encoderService;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        if ($this->user->getUsername() === 'visitor') {
            return $this->user;
        }

        return $this->entityManager->getRepository('ScrumBoardItBundle:Mapping\User')
            ->find($this->user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function flushEntity($entity)
    {
        $user = $this->getEntity();
        $encoder = $this->encoderService->getEncoder($user);
        if ($encoder->isPasswordValid($user->getPassword(), $entity->getOldPassword(), $user->getSalt())) {
            $user->setPassword($encoder->encodePassword($entity->getNewPassword(), $user->getSalt()));
            $user->setPassword($user->getPassword());
            try {
                $this->flushEntity($user);
            } catch (\Exception $e) {
                throw new DatabaseException();
            }
        } else {
            throw new \Exception('Mot de passe intial erroné.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initiate($options = null)
    {
        $user = $options['user'];
        $password = $this->encoderService->getEncoder($user)
            ->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
        $user->addRole('IS_AUTHENTICATED_FULLY');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getFormConfiguration()
    {
        return array(
            'form' => GeneralProfileType::class,
            'entity' => GeneralProfileEntity::class,
        );
    }

    /**
     * Set the template configuration.
     *
     * @param Request $request
     *
     * @return Form
     */
    public function setTemplateConfiguration(Request $request, FormFactory $formFactory)
    {
        $user = $this->getEntity();
        $configurationForm = $formFactory->create(ConfigurationType::class, $user);
        $configurationForm->handleRequest($request);
        $user->setConfiguration(array(
            'user_story' => $configurationForm->get('user_story')->getData(),
            'sub_task' => $configurationForm->get('sub_task')->getData(),
            'poc' => $configurationForm->get('poc')->getData(),
        ));
        if ($this->user->getUsername() !== 'visitor') {
            try {
                $this->entityManager->flush($user);
            } catch (\Exception $e) {
                throw new DatabaseException();
            }
        }

        return $configurationForm;
    }
}
