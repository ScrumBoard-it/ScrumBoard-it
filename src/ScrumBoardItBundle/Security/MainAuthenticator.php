<?php

namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * Main authenticator.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class MainAuthenticator extends AbstractAuthenticator
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderService;

    public function __construct(Router $router, EncoderFactoryInterface $encoderService)
    {
        parent::__construct($router);
        $this->encoderService = $encoderService;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        // Check if request comes from the login form
        if ($request->getPathInfo() == '/login' && $request->isMethod('POST')) {
            return $request->request->get('login');
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $encoder = $this->encoderService->getEncoder($user);
        $user->addRole('IS_AUTHENTICATED_FULLY');

        return $encoder->isPasswordValid($user->getPassword(), $credentials['password'], $user->getSalt());
    }
}
