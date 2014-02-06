<?php

namespace CanalTP\ScrumBoardItBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CanalTPScrumBoardItExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('jira_url', $config['jira_url']);
        $container->setParameter('jira_login', $config['jira_login']);
        $container->setParameter('jira_password', $config['jira_password']);
        $container->setParameter('sprint_id', $config['sprint_id']);
        $container->setParameter('jira_tag', $config['jira_tag']);
    }
}
