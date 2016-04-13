<?php
namespace ScrumBoardItBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Entity\User;

class WebServiceUserProvider implements UserProviderInterface {
    public function loadUserByUsername($username) {
        
        return new User($username, ['ROLE_AUTHENTICATED']);
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $user;
    }

    public function supportsClass($class) {
        return $class === 'ScrumBoardBundle\Entity\User';
    }
}