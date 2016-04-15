<?php

namespace ScrumBoardItBundle\Jira\User;

use ScrumBoardItBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * jira user provider.
 *
 * @author Blaise stanley <stanleyleob@gmail.com>
 */
class JiraUserProvider implements UserProviderInterface
{
    private $jiraRest;

    public function __construct(JiraRest $jiraRest)
    {
        $this->jiraRest = $jiraRest;
    }

    public function loadUserByUsername($username)
    {
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $decodedUserData = base64_decode($user->getBase64Hash());
        list($username, $password) = explode(':', $decodedUserData);
        $userInfoResponse = $this->jiraRest->getUserInfo($username, $password);
        $userInfo = $userInfoResponse;
        $user = new User();
        $user->setUsername($user->getUsername());
        $user->setEmail($userInfo->emailAddress);
        $user->setBase64Hash($user->getBase64Hash());
        $user->setRoles('ROLE_USER');

        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'ScrumBoardBundle\Entity\User';
    }
}
