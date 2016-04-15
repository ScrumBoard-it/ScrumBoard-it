<?php
namespace ScrumBoardItBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class WebServiceUserProvider implements UserProviderInterface {
    public function loadUserByUsername($username) {
        $user = new ScrumBoardItBundle\Entity\User;
        $user->setUsername($username);
        return $user;
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        
        //??? Quelque chose à faire ?
        return $user;
    }

    public function supportsClass($class) {
        return $class === 'ScrumBoardBundle\Entity\User';
    }
}