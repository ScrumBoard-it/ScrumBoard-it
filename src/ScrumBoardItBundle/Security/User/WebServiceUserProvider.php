<?php

namespace ScrumBoardItBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle\Entity\SbiUser;

/**
 * User Provider.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class WebServiceUserProvider implements UserProviderInterface
{   
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        /* Get user by username in database
         * if exist, return new hydrated User
         * if it don't exist, return null
         */
        return $this->em
            ->getRepository('ScrumBoardItBundle:SbiUser')
            ->findOneBy(array('username' => $username));
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SbiUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'ScrumBoardBundle\Entity\SbiUser';
    }
}
