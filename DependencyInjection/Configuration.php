<?php

namespace CanalTP\ScrumBoardItBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('canal_tp_postit');

        $rootNode
            ->children()
                ->scalarNode('jira_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('jira_login')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('jira_password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('sprint_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('jira_tag')
                    ->cannotBeEmpty()
                    ->defaultValue('Post-it')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
