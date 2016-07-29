<?php

namespace ScrumBoardItBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle\Entity\Mapping\User;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * User Provider.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Session
     */
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        if ($username === 'visitor') {
            $user = new User();
            $user->setUsername($username);

            return $user->setApi('discover.api');
        }

        $user = $this->em
            ->getRepository('ScrumBoardItBundle:Mapping\User')
            ->findOneByUsername($username);
        $userConfiguration = array(
            'jira' => $this->em
                ->getRepository('ScrumBoardItBundle:Mapping\JiraConfiguration')
                ->findOneBy(array(
                    'userId' => $user->getId(),
                )),
        );
        $this->session->set('user_configuration', $userConfiguration);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'ScrumBoardBundle\Entity\Mapping\User';
    }
}
