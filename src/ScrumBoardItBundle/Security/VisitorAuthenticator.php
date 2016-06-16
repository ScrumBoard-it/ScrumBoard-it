<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class VisitorAuthenticator extends AbstractTokenAuthenticator
{
    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() == '/discover') {
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
        $user->setDisplayName('Visiteur');
        $user->setApi('discover');
        $user->setImgUrl('bundles/scrumboardit/images/visitor.png');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getApi()
    {
        return 'discover';
    }
}
