<?php
namespace ScrumBoardItBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('scrum_board_it');
        
        $rootNode->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('jira')
                    ->children()
                        ->scalarNode('host')
                            ->isRequired()
                        ->end()
                        ->scalarNode('complexity_field')
                            ->isRequired()
                        ->end()
                        ->scalarNode('printed_tag')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('github')
                    ->children()
                        ->scalarNode('host')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
