<?php
namespace ScrumBoardItBundle\Security;

use ScrumBoardItBundle\Security\AbstractTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class VisitorAuthenticator extends AbstractTokenAuthenticator
{
    /**
     * {@inheritDoc}
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() == '/discover') {
            return array(
                'username' => 'visitor'
            );
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $user->setDisplayName('Visiteur');
        $user->setApi('discover');
        $user->setImgUrl('bundles/scrumboardit/images/visitor.png');
        
        return true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getApi()
    {
        return 'discover';
    }
}
