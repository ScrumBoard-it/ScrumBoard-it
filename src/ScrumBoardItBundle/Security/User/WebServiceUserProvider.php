<?php
namespace ScrumBoardItBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * User Provider
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class WebServiceUserProvider implements UserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        return new User($username, [
            'ROLE_AUTHENTICATED'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (! $user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'ScrumBoardBundle\Entity\User';
    }
}
