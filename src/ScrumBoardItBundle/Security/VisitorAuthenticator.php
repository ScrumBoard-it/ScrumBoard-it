<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Visitor authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class VisitorAuthenticator extends AbstractAuthenticator
{
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() === '/discover' && !$request->isMethod('POST')) {
            return array(
                'username' => 'visitor',
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $user->setApi('discover.api');
        $user->setDisplayName('Visiteur');
        $user->setImgUrl('bundles/scrumboardit/images/visitor.png');
        $user->addRole('IS_CONFIGURED');

        return true;
    }
}
