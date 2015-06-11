<?php

namespace CanalTP\ScrumBoardItBundle\Security\Authentification\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication
\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication
\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class JiraAuthenticationHandler implements AuthenticationSuccessHandlerInterface,
    AuthenticationFailureHandlerInterface
{
    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($request->getSession()->get('_security.jira_secured.login_path'));
    }
    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new RedirectResponse($request->getSession()->get('_security.jira_secured.target_path'));
    }
}
