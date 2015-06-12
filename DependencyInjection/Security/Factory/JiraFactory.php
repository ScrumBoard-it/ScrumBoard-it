<?php

namespace CanalTP\ScrumBoardItBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * jirafactory contains the form parameters.
 */
class JiraFactory extends AbstractFactory
{
    public function __construct()
    {
        $this->addOption('username_parameter', '_username');
        $this->addOption('password_parameter', '_password');
        $this->addOption('intention', 'authenticate');
        $this->addOption('post_only', true);
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'canaltp_jira_auth.authentication_provider.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('canaltp_jira_auth.authentication_provider'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(1, $id)
        ;

        return $provider;
    }

    protected function getListenerId()
    {
        return 'canaltp_jira_auth.authentication_listener';
    }

    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'jira';
    }

    protected function createListener($container, $id, $config, $userProvider)
    {
        $listenerId = parent::createListener($container, $id, $config, $userProvider);

        if (isset($config['csrf_provider'])) {
            $container
                ->getDefinition($listenerId)
                ->addArgument(new Reference($config['csrf_provider']))
            ;
        }

        return $listenerId;
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'security.authentication.form_entry_point.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.form_entry_point'))
            ->addArgument(new Reference('security.http_utils'))
            ->addArgument($config['login_path'])
            ->addArgument($config['use_forward'])
        ;

        return $entryPointId;
    }
}
